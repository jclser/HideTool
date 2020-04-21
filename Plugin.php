<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 回复可见<strong style="color:red;">[hide][/hide]</strong>
 *
 * @package HideTool
 * @author Jclser
 * @version 1.0.0
 * @link http://dearfish.top
 */
class HideTool_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array(__CLASS__, 'replace');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array(__CLASS__, 'replace');
        Typecho_Plugin::factory('admin/write-post.php')->bottom = array(__CLASS__, 'addButton');
        Typecho_Plugin::factory('admin/write-page.php')->bottom = array(__CLASS__, 'addButton');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {

        $msga1 = new Typecho_Widget_Helper_Form_Element_Textarea('msga1',NULL,'<blockquote>此处内容需要评论回复后（审核通过）方可阅读。</blockquote>',_t('隐藏提示'),_t('文章中用[hide][/hide]隐藏的内容模板'));
        $form->addInput($msga1);
        
        $msga2 = new Typecho_Widget_Helper_Form_Element_Textarea('msga2',NULL,'<blockquote>您已登录或回复，可以阅读以下内容：<p>{content}</p></blockquote>',_t('可见效果'),_t('去隐藏后的内容模板(切勿删除{content}标签，否则内容不显示。)'));
        $form->addInput($msga2);

    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @param html $string
     * @return string
     */
    

    public static function replace($content, $Widget, $string)
    {
        $html_string = is_null($string) ? $content : $string;
        $option = Helper::options()->plugin(str_replace('_Plugin','',__CLASS__));
        $db = Typecho_Db::get();
            $sql = $db->select()->from('table.comments')
                ->where('cid = ?', $Widget->cid)
                ->where('status = ?', 'approved')
                ->where('mail = ?', $Widget->remember('mail', true))
                ->limit(1);
            $result = $db->fetchAll($sql);//查看评论中是否有该游客的信息
        if ($result || $Widget->widget('Widget_User@123')->hasLogin()) {
                $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm",''.str_replace('{content}','$1',$option->msga2).'',$html_string);
              } else {
                $content = preg_replace("/\[hide\](.*?)\[\/hide\]/sm",''.$option->msga1.'',$html_string);
              }
        return $content;
    }

    public static function addButton(){
         echo '<script type="text/javascript" src="/usr/plugins/HideTool/assets/editor.js"></script>';
    }

}
