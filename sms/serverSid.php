<?php
//载入ucpass类
require_once('lib/Ucpaas.class.php');

//初始化必填
//填写在开发者控制台首页上的Account Sid
$options['accountsid']='xxxxxxxxxxx';
//填写在开发者控制台首页上的Auth Token
$options['token']='xxxxxxxxxxx';

//初始化 $options必填
$ucpass = new Ucpaas($options);