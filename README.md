# phone GCS | 手机群控系统

#### 前言

无言...



#### 简介

`phoneGCS`全称`phone group control system`，顾名思义即是一款手机(`Android`)群控系统，基于`Cli`形式运行，支持自定义的编排任务、任务录制任务群控。主要有如下特性：

- 自定义任务
- 自定义频率控制
- 指定编排任务
- 指定编排任务执行数量
- 查看设备主要信息



#### 使用

- **录制编排任务**

  ```shell
  ➜  ./library/adb-event-record/adbrecord.py -r ./playbook/{编排任务名称}.pb
  ```


- **编排任务执行指令**

  ```
  # 帮助
  ➜ php artisan task:do help
  usage:
  task:do 
          help
          --devices  view devices main info
          --taskCode=playbook code
          --amount=task amount
          --frequency=execute task frequency | s
          
  # 查看设备信息
  ➜ php artisan task:do --devices 
  192.168.2.141:5555	 OPPO A59m
  
  # 编排任务执行
  ➜ php artisan task:do --taskCode={编排任务名称} --amount={数量} --frequency={频率|单位s}
  Task main message :
  taskCode	simple
  amount		1
  frequency	10
  simple playbook running...
  ```
