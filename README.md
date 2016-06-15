#zqtsx
PHP实现类似CS模式下的插件引擎功能


为插件引擎准备好要用到的测试函数
function str2str2($str){
  return '<p>P标签开始 '.$str.' P标签结束<p/>';
}
function str3str3($str){
  return '<b style="color:red">b标签开始 '.$str.' b标签结束<b/>';

}

注意：在测试三个例子时，一定要一个一个的测试，测试时请注释掉其他多余的例子，否则将无法看到插件引擎权限优先级的 实际对比效果产生异常结果！
例子一：
str2str2函数的执行优先级小于str3str3，这里先执行str3str3($str)函数后执行str2str2($str)函数;
实际运行流程解刨如下：
$str=str3str3('这是要像插件里所有函数传入的参数这里函数str3str3的执行优先级高于str2str2');
$str=str2str2($str);
echo $str; 
输出结果浏览器里查看HTML源代码得到如下内容：
 <p>P标签开始 <b style="color:red">b标签开始 这是要像插件里所有函数传入的参数这里函数str3str3的执行优先级高于str2str2 b标签结束<b/> P标签结束<p/>

addPlugin('cleanText','str2str2',array('str'=>''),1);
addPlugin('cleanText','str3str3',array('str'=>''),10);
echo doPlugin('cleanText',array('str'=>'这是要像插件里所有函数传入的参数这里函数str3str3的执行优先级高于str2str2'));
例子二：
addPlugin('cleanText','str2str2',array('str'=>''),10);
addPlugin('cleanText','str3str3',array('str'=>''),1);
echo doPlugin('cleanText',array('str'=>'这是要像插件里所有函数传入的参数这里函数str2str2的执行优先级高于str3str3'));
运行结果HTML页面源代码如下：
<b style="color:red">b标签开始 <p>P标签开始 这是要像插件里所有函数传入的参数这里函数str2str2的执行优先级高于str3str3 P标签结束<p/> b标签结束<b/>

例子三：
addPlugin('cleanText','str2str2',array('str'=>''),1);
addPlugin('cleanText','str3str3',array('str'=>''),1);
echo doPlugin('cleanText',array('str'=>'当权限排序值大小一致时，后面的函数权限优先级要小于前面的故先添加的函数先执行，这里函数str3str3的执行优先级小于str2str2'));
执行后的HTML源代码结果如下：
<b style="color:red">b标签开始 <p>P标签开始 当权限排序值大小一致时，后面的函数权限优先级要小于前面的故先添加的函数先执行，这里函数str3str3的执行优先级小于str2str2 P标签结束<p/> b标签结束<b/>

例子四：
同一标签下的不同函数参数不同，可以根据实际情况传入不同数量的参数,但是传入参数的数量不能少于函数必选项参数的数量
addPlugin('e','json_encode',array('str'=>''),10);
addPlugin('e','json_decode',array('str'=>'','bool'=>false),9);
执行
$arr1=doPlugin('e',array('str'=>array('a'=>'aaa'),'bool'=>false));
或者
$arr2=doPlugin('e',array('str'=>array('a'=>'aaa')));
print_r($arr1);
和
print_r($arr2);
结果相同返回如下对象:

stdClass Object
(
    [a] => aaa
)

如果替换成如下代码
$arr=doPlugin('e',array('str'=>array('a'=>'aaa'),'bool'=>true));
print_r($arr);
结果如下返回数组：

Array
(
    [a] => aaa
)

依次查看
print_r($arr1);
print_r($arr2);
print_r($arr);
效果如下

stdClass Object
(
    [a] => aaa
)
stdClass Object
(
    [a] => aaa
)
Array
(
    [a] => aaa
)


测试doAction执行插件的例子(该插件没有返回值，只执行！)
注，该插件为伍返回值插件，故而只用做输出 或直接执行场合，优先级同doPlugin插件优先级设置，故不详述！
function alertstr($str){
  echo "<script>alert('$str');</script>";
}
function alertstr2($str){
  echo $str.'1+2';
}
addAction('alert','alertstr',array('str'=>''),1);
addAction('alert','alertstr2',array('str'=>''),10);
doAction('alert',array('str'=>'要弹出的参数'));
运行后的HTML源代码结果如下：

要弹出的参数1+2<script>alert('要弹出的参数');</script>
