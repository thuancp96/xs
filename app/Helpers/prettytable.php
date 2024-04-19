<?php
/*
 *	https://bitbucket.org/maksoft_codeTeam/prettytable-php
 *
 * */


/*
 * TODO: Add Sheet Functionality 
 *
 * */

class PrettyTable 
{
    public $settings = array();
    public $generate_css = False;
    public $field_names;
    public $sheets = array();
    public $css_class = False;
    public $rows = array();
    private $__css = array();



    public function __construct()
    {
        $this->field_names = func_get_args();
        $this->i = 1000;
    }
    
    /**
    * @return table as string when echoing class instance
    * 
    * @codeCoverageIgnore
    **/
    public function __toString()
    {
        try{
            return $this->generate_table($tag='table', $rows=$this->rows);
        } catch (Exception $e){
            return "Row count doesnt match Field name`s count";
        }
    }

    /**
    * @return predefined css property value
    *
    * @codeCoverageIgnore
    *
    **/
    public function __get($index)
    {
        return $this->__css[$index];
    }
    
    /**
    * set @this->_css property value
    *
    **/   
    public function __set($index, $value)
    {
        $this->__css[$index] = $value;
    }


    public function settings($sett_name, $value)
    {
        $this->settings[$sett_name] = $value;
    }


    /**
     * set table columns names
     *
     **/
    public function set_field_names($args)
    {
        $this->field_names = $args;
    }

    /**
     * add row to table it takes list
     *
     **/
    public function add_row($args)
    {
        $this->rows[] = $args;
    }

    public function add_rows()
    {
        $this->rows[] = func_get_args();
    }

    /**
     * set table class name
     *
     **/
    public function set_class($class_name=False)
    {
        $this->css_class = $class_name;
    }

    /**
     * @return table class name if is set
     *
     */
    public function get_class()
    {
        return $this->css_class;
    }

    /**
     * truncate rows
     *
     **/
    public function clear_rows()
    {
        $this->rows = array();
    }

    public function as_p()
    {
        $this->is_valid();
    }

    public function add_sheet($name=null)
    {
        if(!is_null($name)){
            $this->sheets[$name]= array();
            return true;
        }
        return False;
    }

    public function insert_in_sheet($name=null, $row=null)
    {
        if(!is_null($name) && !is_null($row)){
            $this->sheets[$name][]=$row;
            return True;
        }
        return False;
    }

    public function print_sheet($name=null)
    {
        if(array_key_exists($name, $this->sheets))
            return $this->generate_table($tag='table', $rows=$this->sheets[$name]);
        throw new ValidationError("There is no sheet with name - ".$name);
    }

    public function count()
    {
        return count($this->rows);
    }

    public function as_t()
    {
        $table = '<table>';
        $this->is_valid();
        if($this->css_class)
            $table .= ' class="'.$this->css_class.'">';
        $table .= '<tr>';
    }

    private function generate_fields($tag='td')
    {
        for ($i=0;$i<count($this->field_names); $i++):
            $this->field_names[$i] = $this->open_tag($tag).'>'.
                    $this->field_names[$i].$this->close_tag($tag);
        endfor;
        return '<tr>'.implode('', $this->field_names).'</tr>';
    }

    private function generate_rows($rows)
    {
        $this->is_valid($rows); 
		$t = '';
        for ($i=0;$i<count($rows); $i++):
            $t .= '<tr>';	
	    	foreach($rows[$i] as $row):
                $t .= $this->__add_class_td($row);
            endforeach;
            $t .= '</tr>';
        endfor;
        return $t;
    }

    private function generate_table($tag='table', $rows=null)
    {
        if(is_null($rows)){ $rows = $this->rows;} 
        $table = $this->open_tag($tag);
        if ($this->generate_css):
            $table .= $this->__set_css();
        endif;
        if($this->css_class)
            $table .= ' class="'.$this->css_class.'">'; 
        $table .= "<tr>".
                  $this->generate_fields()."</tr>".
                  $this->generate_rows($rows).$this->close_tag($tag);
        return $table;
    }


    /**
     * @codeCoverageIgnore
     */
    private function close_tag($tag)
    {
        return '</'.$tag.'>';
    }

    /**
     * @codeCoverageIgnore
     */
    private function open_tag($tag)
    {
        return '<'.$tag.' ';
    }

    private function is_valid()
    {
        if (!$this->__row_validator()):
            throw new ValidationError("Row count doesnt match field names count", 1);
        endif;
        return True;
    }


    /**
     *  @return (string) rows
     *
     *  First check if lenght of rows is equal to field names if not
     *  raises ValidationError and function stops. Then foreach rows
     *  take one row and add <td> </td> tags wrapped in <tr> </tr>
     **/
    private function __add_class_td($row)
    {
    	if(!is_array($row)):
    		return "<td>".$row."</td>";
    	endif;
    	return "<td class=".$row[1].">".$row[0]."</td>";
    }

    /**
     * @return bool
     *
     **/
    private function __row_validator()
    {   
        for ($i=0; $i < count($this->rows) ; $i++):
            if (count($this->rows[$i]) != count($this->field_names)):
                return false;
            endif;
        endfor;
        return true;
    }
}


