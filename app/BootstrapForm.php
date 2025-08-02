<?php
namespace App;

class BootstrapForm {
    private $surround = "div";
    private static $_instance = null;
    
    // Empêche l'instanciation directe
    private function __construct() {}
    
    // Singleton
    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    // Entoure le HTML avec la balise spécifiée
    public function surround($html) {
        return "<{$this->surround} class=\"form-group mb-3\">{$html}</{$this->surround}>";
    }
    
    // Génère un input
    public function input($type, $name, $label = null, $value = null, $attributes = []) {
        $labelHtml = "<label for=\"{$name}\" class=\"form-label\">{$label}</label>" ;
        $valueAttr = $value !== null ? " value=\"" . htmlspecialchars($value) . "\"" : "";
        
        $attrs = '';
        foreach ($attributes as $attr => $val) {
            $attrs .= " {$attr}=\"{$val}\"";
        }
        
        $input = "<input type=\"{$type}\" class=\"form-control\" id=\"{$name}\" name=\"{$name}\"{$valueAttr}{$attrs}>";
        
        return $this->surround($labelHtml . $input);
    }
    
    // Génère un textarea
    public function textarea($name, $label = null, $value = null, $attributes = []) {
        $labelHtml = $label ? "<label for=\"{$name}\" class=\"form-label\">{$label}</label>" : "";
        $valueContent = $value !== null ? htmlspecialchars($value) : '';
        
        $attrs = '';
        foreach ($attributes as $attr => $val) {
            $attrs .= " {$attr}=\"{$val}\"";
        }
        
        $textarea = "<textarea class=\"form-control\" id=\"{$name}\" name=\"{$name}\"{$attrs}>{$valueContent}</textarea>";
        
        return $this->surround($labelHtml . $textarea);
    }
    public function select($name, $label, $table){
        $autre = null;
        if($table==null){
            $autre = null;
        }else{
            foreach($table as $cat){
                $autre.="<option value=$cat->id>$cat->title</option>";
            }
        }
        $text = "<select name=$name class='form-select mb-4' aria-label='Default select example'><option selected>$label</option>$autre</select>";
        return $text;
    }
    // Génère un bouton submit
    public function submit($text = "Envoyer", $class = "btn btn-primary") {
        return $this->surround("<button type=\"submit\" name=submit class=\"{$class}\">{$text}</button>");
    }
}