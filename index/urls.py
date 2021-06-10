# coding=utf-8
from django.conf.urls import url, include

import views

urlpatterns = [

    url(r'index/', views.index),
    url(r'register/', views.register),
    url(r'logout/', views.logout),
    url('confirm/', views.user_confirm),
    url(r'captcha/', include('captcha.urls')),
    url(r'detection/', views.detection),
    url(r'monitor/', views.monitor)

]
