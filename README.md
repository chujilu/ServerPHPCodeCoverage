
## 简介

对接口测试进行代码覆盖率分析

部署在服务器端，对项目代码无入侵

## install

1、下载源码到服务器
`git clone git@github.com:chujilu/ServerPHPCodeCoverage.git`

2、安装依赖 `composer install`

3、配置加载文件 php.ini文件内配置
`auto_prepend_file = {项目代码路径}/lib/inject.php`

4、nginx配置php服务到public目录，给data var目录777权限

## 配置

* 任务 配置任务代码路径，设置状态为采集中，点击保存配置，检查生成的php配置是否正确
* 报告 运行一段时间后点击任务操作生成报告，点击报告操作查看报告
* 保存配置

[项目预览](https://www.it603.com/page/93.html) https://www.it603.com/page/93.html

![任务](https://www.it603.com/file.php?f=201907/f_72e76e246945e6792b0ee759cfff69ca.png&t=png&o=&s=full&v=1426664829)

![任务列表](https://www.it603.com/file.php?f=201907/f_4f0a118dd37c360c3e5dd322535f0640.png&t=png&o=&s=full&v=1426664829)

![报告](https://www.it603.com/file.php?f=201907/f_c13fa336bd3f0b248ce69c809ecc2912.png&t=png&o=&s=full&v=1426664829)

![报告详情](https://www.it603.com/file.php?f=201907/f_6697a713838a188b8eef89cf968b87fa.png&t=png&o=&s=full&v=1426664829)

![报告报表](https://www.it603.com/file.php?f=201907/f_a08a1c854aaf0f3ef72309ea0cade5c9.png&t=png&o=&s=full&v=1426664829)

