1，查找字符串位置的函数（不适合用于汉字查找）
strpos（str,find,[int]）:查找find在str中第一次出现的位置。（对大小写敏感）从int位置开始往后查找。如果没有找到返回flase。       
strrpos(str,find,[int]):查找find在str中最后一次出现的位置。（对大小敏感）从int位置开始往后查找。如果没有找到返回false。　　
stripos(str,find,[int]):查找find在str中第一次出现的位置。（对大小写不敏感）。　　
strripos(str,find,[int]):查找find在str中最后一次出现的位置。（对大小写不敏感）。
2，提取子字符串函数
substr(str,start,length):从start位置开始的地方截取length长度的字符串，若length为空，则截取到末尾。若start参数是负数且length小于或者等于start，则length为0。　　　　　
start：正数-在字符串的指定位置开始。负数-在从字符串结尾开始的指定位置开始。0-在字符串中的第一个位置开始。　　　　  
length：正数-从start参数所在的位置返回的长度。负数-从字符串末端返回的长度　　　　　
eg：str=‘abcdefg’;    　　　　　　
substr($str,'2')//cdefg  　　　　　　 
substr($str,'-1')//g  　　　　　　 
substr($str,'2','-1')//cdef   　　　　　　 
substr($str,'2','0') //''    　　　　　　 
substr($str,'-2','-1') //'f     　　　　　　 
substr($str,'2','-3') // '' 　　　　　　 
substr($str,'-2','1')//f　　　　　
注：截取中文的时候不行，会有乱码。　　
mb_substr(str,start,length)同上，截取中文字符串　　
strstr(str,find,[true])：搜索find字符串在str字符串中的第一次出现的位置。并从其位置开始截取到结尾。若没有，则返回false（区分大小写），默认false，为true时，返回find第一次出现之前的字段。　　　　　eg：str="abcdefg";     
strstr($str,'cd)//cdefg     
strstr($str,'cd',true)//ab　    
stristr(str,find,[true])：同上，不区分大小写。　　
strchr()：该函数用法与strstr()函数一样。　　
strrchr(str,find)：查找find字符串在str字符串中最后一次出现的位置，并返回从该位置到字符串结尾的所有字符。
3，字符串替换　   
strtr(str,from,to)：都是必须的。例如strtr("Hello Wang",'a','e');//把字符串中的字符‘a’替换成'e'　   
str_replace(find,replace,string,count)：字符串find查找string中并用replace替换，count统计替换数量。（也可以操作数组。区分大小写）　　
str_ireplace():函数同上，不区分大小写。　　
substr_replace(string,replace,start,length):从start位置开始，长度为length的字符串string的一部分替换成replace。
4，其它　　strlen(str)：返回字符串的长度。　　
mb_strlen(str):返回中文字符串的长度。　  
nl2br(str):在字符串中的新行（\n）之前插入换行符　　
str_pad($str,length,pad_string,pad_type)：函数把字符串str按length的长度填充pad_string;pad_type填充到哪边；　　　　      
pad_type：　　　　　　　　
STR_PAD_BOTH - 填充字符串的两侧。如果不是偶数，则右侧获得额外的填充。　　　　　　　　
STR_PAD_LEFT - 填充字符串的左侧。　　　　　　　　
STR_PAD_RIGHT - 填充字符串的右侧。默认　　
strrev(str)：反转字符串　　
strtolower(str)：把字符串转换为小写字母。　　
strtoupper(str);把字符串转换为大写字母。　　
ucfirst(str)：把字符串中的首字母转换为大写。　　
ucwords(str)：把字符串中每个单词的首字母转换为大写。　　
substr_count(str,substr,[start],[length])：计算子串在字符串中出现的次数。start-可选，规定字符串在何处开始搜索。length-可选，规定搜索的长度。
个人随笔做个记录
