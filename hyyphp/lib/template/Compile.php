<?php
/**
 * 模板编译
 * @Author: LongDH
 * @Date:   2017-11-05 18:35:31
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 18:56:01
 */
namespace hyyphp\lib\template;

class Compile {
     //模板内容
     private $content = '';

     public function __construct($tpl_file) {
          $this->content = file_get_contents($tpl_file);
     }

     //解析普通变量，如把{$name}解析成$this->_tpl_var['name']
     public function parseVar() {
          $pattern = '/\{\$([\w\d]+)\}/';
          if (preg_match($pattern, $this->content)) {
               $this->content = preg_replace($pattern, '<?php echo \$this->_tpl_var["$1"]?>', $this->content);
          }
     }

     //这里可以自定义其他解析器...

     //模板编译
     public function parse($parse_file) {
          //调用普通变量解析器
          $this->parseVar();
          //这里可以调用其他解析器...

          //编译完成后，生成编译文件
          if (!file_put_contents($parse_file, $this->content)) {
               exit('编译文件生成出错！');
          }
     }
}