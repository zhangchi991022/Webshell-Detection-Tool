# Webshell-Detection-Tool

本工具采取B/S 架构，框架采用Django，基于之前的机器学习检测算法：https://github.com/zhangchi991022/webshellDetection



## 功能简介

### 文件检测

包含对单个PHP文件以及单个zip压缩包的检测

### 目录监控

用python watchdog插件实现对系统敏感目录的监控

## 模块简介

black-list：PHP黑名单，数据集来源自GitHub上开源代码

white-list：PHP白名单，数据集来自于PHPmyadmin等开源软件

uploads：存放检测的文件记录

index：项目主目录，主要包含webshell检测算法和Django框架主要交互代码

## 运行

首次运行web应用需要先对数据集进行训练，在index目录下运行train.py，会生成黑白名单的opcode文件分别为black_opcodes.txt和white_opcodes.txt存放在该目录下并且将训练的模型保存在save目录下，之后的web应用会调用该模型和opcode数据集。

运行整个web应用：

python2 manage.py runserver

![image](https://github.com/zhangchi991022/Webshell-Detection-Tool/blob/main/image/test1.PNG)
![image](https://github.com/zhangchi991022/Webshell-Detection-Tool/blob/main/image/test2.PNG)
![image](https://github.com/zhangchi991022/Webshell-Detection-Tool/blob/main/image/test3.PNG)

