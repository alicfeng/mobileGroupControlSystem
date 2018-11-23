# wechatIntelligentMarketing

#### 前言

...



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
