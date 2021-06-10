# -*- coding: utf-8 -*-
from __future__ import print_function

import datetime
import hashlib
import json
import os
import string
import threading
import time
import zipfile
from io import BytesIO

import xlwt
from django.conf import settings

from django.http import HttpResponse, JsonResponse
from django.shortcuts import render, redirect
from django.views.decorators.csrf import csrf_exempt

from index import models, forms
from index.check import checkfile
from index.models import UploadFile, User, MonitorFile

from watchdog.observers import Observer
from watchdog.events import *
from watchdog.utils.dirsnapshot import DirectorySnapshot, DirectorySnapshotDiff

from apscheduler.schedulers.background import BackgroundScheduler
from django_apscheduler.jobstores import DjangoJobStore, register_events, register_job

# job_defaults = {'max_instances': 5}
scheduler = BackgroundScheduler()  # 创建一个调度器对象
scheduler.add_jobstore(DjangoJobStore(), "default")  # 添加一个作业
try:
    @register_job(scheduler, "interval", seconds=0.5)
    def task():
        monitorfile()


    register_events(scheduler)
    scheduler.start()
except:

    scheduler.shutdown()


def index(request):
    if not request.session.get('is_login', None):
        return redirect('/login/')
    user_count = User.objects.count()
    file_count = UploadFile.objects.count()
    monitor_count = MonitorFile.objects.count()

    return render(request, 'index/index.html',
                  {'user_count': user_count, 'file_count': file_count, 'monitor_count': monitor_count})


def login(request):
    if request.session.get('is_login', None):  # 不允许重复登录
        return redirect('/index/')
    if request.method == 'POST':
        login_form = forms.UserForm(request.POST)
        message = '请检查填写的内容！'
        if login_form.is_valid():
            username = login_form.cleaned_data.get('username')
            password = login_form.cleaned_data.get('password')

            try:
                user = models.User.objects.get(name=username)
            except:
                message = '用户不存在！'
                return render(request, 'index/login.html', locals())

            if not user.has_confirmed:
                message = '该用户还未经过邮件确认！'
                return render(request, 'index/login.html', locals())

            if user.password == hash_code(password):
                request.session['is_login'] = True
                request.session['user_id'] = user.id
                request.session['user_name'] = user.name
                return redirect('/index/')
            else:
                message = '密码不正确！'
                return render(request, 'index/login.html', locals())
        else:
            return render(request, 'index/login.html', locals())

    login_form = forms.UserForm()
    return render(request, 'index/login.html', locals())


def send_email(email, code):
    from django.core.mail import EmailMultiAlternatives

    subject = u'来自webshellDetectionTool的注册确认邮件'

    text_content = u'''感谢注册webshellDetectionTool，这里是本人的毕设站点！\
                    如果你看到这条消息，说明你的邮箱服务器不提供HTML链接功能，请联系管理员！'''

    html_content = u'''
                    <p>感谢注册<a href="http://{}/confirm/?code={}" target=blank>webshell检测工具</a>，\
                    </p>
                    <p>请点击站点链接完成注册确认！</p>
                    <p>此链接有效期为{}天！</p>
                    '''.format('127.0.0.1:8000', code, settings.CONFIRM_DAYS)

    msg = EmailMultiAlternatives(subject, text_content, settings.EMAIL_HOST_USER, [email])
    msg.attach_alternative(html_content, "text/html")
    msg.send()


def make_confirm_string(user):
    now = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    code = hash_code(user.name, now)
    models.ConfirmString.objects.create(code=code, user=user, )
    return code


def register(request):
    if request.session.get('is_login', None):
        return redirect('/index/')

    if request.method == 'POST':
        register_form = forms.RegisterForm(request.POST)
        message = "请检查填写的内容！"
        if register_form.is_valid():
            username = register_form.cleaned_data.get('username')
            password1 = register_form.cleaned_data.get('password1')
            password2 = register_form.cleaned_data.get('password2')
            email = register_form.cleaned_data.get('email')
            sex = register_form.cleaned_data.get('sex')

            if password1 != password2:
                message = '两次输入的密码不同！'
                return render(request, 'index/register.html', locals())
            else:
                same_name_user = models.User.objects.filter(name=username)
                if same_name_user:
                    message = '用户名已经存在'
                    return render(request, 'index/register.html', locals())
                same_email_user = models.User.objects.filter(email=email)
                if same_email_user:
                    message = '该邮箱已经被注册了！'
                    return render(request, 'index/register.html', locals())

                new_user = models.User()
                new_user.name = username
                new_user.password = hash_code(password1)
                new_user.email = email
                new_user.sex = sex
                new_user.save()

                code = make_confirm_string(new_user)
                send_email(email, code)

                message = '请前往邮箱进行确认！'
                return render(request, 'index/confirm.html', locals())
        else:
            return render(request, 'index/register.html', locals())
    register_form = forms.RegisterForm()
    return render(request, 'index/register.html', locals())


def logout(request):
    if not request.session.get('is_login', None):
        # 如果本来就未登录，也就没有登出一说
        return redirect("/login/")
    request.session.flush()
    return redirect("/login/")


def hash_code(s, salt='mysite'):  # 加点盐
    h = hashlib.sha256()
    s += salt
    h.update(s.encode())  # update方法只接收bytes类型
    return h.hexdigest()


def user_confirm(request):
    code = request.GET.get('code', None)
    message = ''
    try:
        confirm = models.ConfirmString.objects.get(code=code)
    except:
        message = '无效的确认请求!'
        return render(request, 'index/confirm.html', locals())

    c_time = confirm.c_time
    now = datetime.datetime.now()
    if now > c_time + datetime.timedelta(settings.CONFIRM_DAYS):
        confirm.user.delete()
        message = '您的邮件已经过期！请重新注册!'
        return render(request, 'index/confirm.html', locals())
    else:
        confirm.user.has_confirmed = True
        confirm.user.save()
        confirm.delete()
        message = '感谢确认，请使用账户登录！'
        return render(request, 'index/confirm.html', locals())


def filesize(self):
    x = self.size
    y = 512000
    if x < y:
        value = round(x / 1000, 2)
        ext = ' kb'
    elif x < y * 1000:
        value = round(x / 1000000, 2)
        ext = ' Mb'
    else:
        value = round(x / 1000000000, 2)
        ext = ' Gb'
    return str(value) + ext


def unzip_file(zipfilename, unziptodir):
    if not os.path.exists(unziptodir):
        os.mkdir(unziptodir, 0o777)
    zfobj = zipfile.ZipFile(zipfilename)
    for name in zfobj.namelist():

        if name.endswith('/'):
            os.mkdir(os.path.join(unziptodir, name))
        else:
            ext_filename = os.path.join(unziptodir, name)
            ext_dir = os.path.dirname(ext_filename)
            if not os.path.exists(ext_dir):
                os.mkdir(ext_dir, 0o777)
            outfile = open(ext_filename, 'wb')
            outfile.write(zfobj.read(name))
            outfile.close()


def detection(request):
    if request.method == 'POST':
        myfile = request.FILES.get('myfile', None)
        if myfile:
            print(os.path.splitext(myfile.name)[0])
            file_size = filesize(myfile)
            file = UploadFile.objects.create(file_path=myfile, file_size=file_size,
                                             name=request.session.get('user_name'))
            file.save()
        else:
            return render(request, 'detection/detection.html', locals())

        testfile = os.path.join("./uploads", myfile.name)
        if testfile.endswith('.php'):
            y_p = checkfile(testfile)
            if y_p[0][0] > 0.5:
                message = u"Not Webshell!"
                return render(request, 'detection/detection.html', {'message': message})
            else:
                message = u"Webshell Discovered!"
                return render(request, 'detection/detection.html', {'message': message})
        elif testfile.endswith('.zip'):
            wb = xlwt.Workbook(encoding='utf-8')
            sheet = wb.add_sheet(u'检测结果')
            style_heading = xlwt.easyxf("""
            font:
              name Arial,
              colour_index white,
              bold on,
              height 0xA0;
            align:
              wrap off,
              vert center,
              horiz center;
            pattern:
              pattern solid,
              fore-colour 0x19;
            borders:
              left THIN,
              right THIN,
              top THIN,
              bottom THIN;
            """)
            style_body = xlwt.easyxf("""
            font:
              name Arial,
              bold off,
              height 0XA0;
            align:
              wrap on,
              vert center,
              horiz center;
            borders:
              left THIN,
              right THIN,
              top THIN,
              bottom THIN;
            """)

            style_green = xlwt.easyxf(" pattern: pattern solid,fore-colour 0x11;align: vert center,horiz center;")
            style_red = xlwt.easyxf(" pattern: pattern solid,fore-colour 0x0A;align: vert center,horiz center;")
            fmts = [
                'M/D/YY',
                'D-MMM-YY',
                'D-MMM',
                'MMM-YY',
                'h:mm AM/PM',
                'h:mm:ss AM/PM',
                'h:mm',
                'h:mm:ss',
                'M/D/YY h:mm',
                'mm:ss',
                '[h]:mm:ss',
                'mm:ss.0',
            ]
            style_body.num_format_str = fmts[0]
            sheet.write(0, 0, u'文件路径', style_heading)
            sheet.write(0, 1, u'类型', style_heading)
            row = 1

            new_dir = os.path.join(".\uploads", str(UploadFile.objects.count()))
            unzip_file(testfile, new_dir)
            white_list = []
            black_list = []
            for root, dirs, files in os.walk(new_dir):
                for filename in files:
                    if filename.endswith('php'):
                        try:
                            full_path = os.path.join(root, filename)
                            sheet.write(row, 0, full_path, style_body)

                            y_p = checkfile(full_path)
                            if y_p[0][0] > 0.5:
                                flag = u'normal'
                                white_list.append(full_path)
                                sheet.write(row, 1, flag, style_green)

                            else:
                                flag = u'webshell'
                                black_list.append(full_path)
                                sheet.write(row, 1, flag, style_red)
                            row = row + 1

                        except:
                            continue
            sio = BytesIO()
            wb.save(sio)
            sio.seek(0)
            response = HttpResponse(sio.getvalue(), content_type='application/vnd.ms-excel')
            response['Content-Disposition'] = 'attachment; filename=result.xls'
            response.write(sio.getvalue())

            return response
    else:
        return render(request, 'detection/detection.html', locals())


class FileEventHandler(FileSystemEventHandler):

    def on_any_event(self, event):
        pass

    def on_moved(self, event):
        pass

    def on_created(self, event):
        pass

    def on_deleted(self, event):
        pass

    def on_modified(self, event):
        if event.is_directory:
            pass
        else:
            testfile = event.src_path
            if testfile.endswith('.php'):
                y_p = checkfile(testfile)
                if y_p[0][0] > 0.5:
                    message = u'not webshell'
                    print("file modified: " + event.src_path + '  result: ' + message)
                    monitoredfile = MonitorFile.objects.create(path=testfile, f_type='normal')
                    monitoredfile.save()

                    for row in MonitorFile.objects.all().filter(path=testfile):
                        if MonitorFile.objects.filter(path=testfile).count() > 1:
                            row.delete()


                else:
                    message = u'webshell'
                    print("file modified: " + event.src_path + '  result: ' + message)
                    monitoredfile = MonitorFile.objects.create(path=testfile, f_type='webshell')
                    monitoredfile.save()

                    for row in MonitorFile.objects.all().filter(path=testfile):
                        if MonitorFile.objects.filter(path=testfile).count() > 1:
                            row.delete()


def monitorfile():
    observer = Observer()

    event_handler = FileEventHandler()
    observer.schedule(event_handler, r'D:\Apache24\htdocs', True)
    observer.start()

    try:
        while True:
            time.sleep(2)
    except KeyboardInterrupt:
        observer.stop()


@csrf_exempt
def monitor(request):
    if request.method == 'POST':
        body = json.loads(request.body)
        path = body.get('path')
        obj = MonitorFile.objects.get(path=path)
        obj.delete()
        data = {'res': '删除成功'}

        return HttpResponse(json.dumps(data))
    else:
        result = MonitorFile.objects.all()
        blackCount = MonitorFile.objects.filter(f_type='webshell').count()
        whiteCount = MonitorFile.objects.filter(f_type='normal').count()
        dic = {'result': result, 'blackCount': blackCount, 'whiteCount': whiteCount}

        return render(request, 'monitor/monitor.html', dic)
