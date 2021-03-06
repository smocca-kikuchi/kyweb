<?php
/** 
* KYHtmlクラス
*
* @license http://apache.org/licenses/LICENSE-2.0
*
* @copyright ©kyphone
*/

KYWeb::refuse_direct_access(".inc.php");

/**
* KYHtmlクラスはKYPageの親クラスで、ページ処理をし最終htmlを出力ます。
*
* @license http://apache.org/licenses/LICENSE-2.0
*
* @copyright ©kyphone
*/
class KYHtml {
	/** @var string htmlソース */
	protected $_html;
	
	/** @var array 書き換え配列 */
	protected $_assign;
	
	/** @var string 処理結果html */
	protected $_result;
	
	/**
	* コンストラクタ
	*/
	public function __construct() {
		$this->_html = "";
		$this->_assign = array();
		$this->_result = "";
	}
	
	/**
	* htmlソースの set, get をします。
	*
	* @param string $value htmlソース、パラメータなしの場合 get になります
	*
	* @return mixed setの場合、自分自身(KYHtmlオブジェクト) | getの場合、htmlソース
	*/
	public function html($value = NULL) {
		if ($value == NULL) {
			return $this->_html;
		}
		
		$this->_html = $value;
		return $this;
	}
	
	/**
	* html内で書き換える {タグ名} と値を指定します。
	*
	* @param string|array $param1 タグ名、もしくは、(タグ名, 値)の配列
	* @param string $param2 書き替える値、$param1 が配列の場合は必要ない
	*
	* @return object 自分自身(KYHtmlオブジェクト) 
	*
	* @example private/library/example/kyhtml_assign.php
	*/
	public function assign() {
		$args = func_get_args();
		$args_cnt = count($args);
		
		if ($args_cnt == 1 && is_array($args[0])) {
			foreach ($args[0] as $tag => $value) {
				$this->assign($tag, $value);
			}
		} else if ($args_cnt == 2 && !is_array($args[0])) {
			$tag = $args[0];
			$value = $args[1];
			$this->_assign["{{$tag}}"] = $value;
		}
		return $this;
	}
	
	/**
	* html内の書き換える部分の処理を実行します。
	*
	* 結果は `result` 関数で求めます。
	*
	* ※この関数は直接使用しません、フレームワーク内の他のクラスで使用されます。
	*
	* @return object 自分自身(KYHtmlオブジェクト)
	*/
	public function process() {
	    global $_STR;

	    if (!empty($_STR)) {
            foreach ($_STR as $key => $value) {
                $this->assign("STR:{$key}", $value);
            }
        }

		if ($this->_assign == NULL) {
			$this->_result = $this->_html;
		} else {
			$this->_result = strtr($this->_html, $this->_assign);
		}

		return $this;
	}
	
	/**
	* 処理結果を取得します。
	*
	* ※この関数は直接使用しません、フレームワーク内の他のクラスで使用されます。
	*
	* @return string 処理結果html
	*/
	public function result() {
		return $this->_result;
	}
}
?>