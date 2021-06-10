# -*- coding: utf-8 -*-
from __future__ import unicode_literals

from django.contrib import admin

from index import models

admin.site.register(models.User)
admin.site.register(models.ConfirmString)
admin.site.register(models.UploadFile)
admin.site.register(models.MonitorFile)
