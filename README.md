
# 描述

将某个文件夹下的图片通过弹框的形式供用户选择

# 功能
选择图片,多选图片,删除图片,大图浏览

# 细节
1. 按住`shift`键进行连选
2. 双击图片放大浏览,再次双击返回
3. 图片可以多张删除
4. 选择完图片之后会展示在下方,选择的图片会以逗号链接的形式存储在隐藏的输入框中,输入框的name为`selected-images`

# 代码
代码依赖于jquery,bootstrap,vue,sweetAlert


# 配置
```
<?php

return [

    //显示的图片类型
    'type' => [
        'gif', 'jpg', 'png'
    ],

    //递归获取path下的所有图片
    'recursion' => true,

    //图片缓存时间,使用默认缓存
    'cache_time' => 5,

    //使用的文件存储配置如下
//    'uploads' => [
//        'driver' => 'local',
//        'root' => public_path('uploads'),
//        'url' => env('APP_URL').'uploads',
//        'visibility' => 'public',
//    ],
    'disk' => 'uploads',
    
    
    //默认引入的文件有  'jQuery-2.1.4.min.js','bootstrap.min.js','vue.js','showphotos.js','sweetalert.min.js',如果项目中已经引入,可在下方排出
    'js-except' => [
        'bootstrap',
        'jquery'
    ],
    //默认引入的文件有      'bootstrap.min.css','font-awesome.min.css','sweetalert.css'
    'css-except' => [
        'bootstrap',
        'font-awesome'
    ]

];
```

