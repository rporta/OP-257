<?php

class configJson
{
        private static $instance;

        private $content;
        private $parent;
        private $child;

        public static function getInstance()
        {
        if ( !isset( self::$instance ) ) 
        {
            $c = __CLASS__;
            self::$instance = new $c;
        }

        return self::$instance;
    }

    public function setConfigFile( $configFile )
    {
        if ( file_exists( $configFile ) )
        {
                $this->content = file_get_contents( $configFile );
                        $this->content = json_decode( $this->content, true );
                        $extraConfigFiles = ( $this->get( 'include_files' ) ) ? $this->get( 'include_files' ) : false;
                        if ( is_array( $extraConfigFiles ) && count( $extraConfigFiles ) > 0 )
                        {
                                unset ( $this->content['include_files'] );
                                foreach( $extraConfigFiles as $index => $newConfigFile )
                                {
                                        $file = str_replace( basename( $configFile ), $newConfigFile, $configFile );
                                        if ( file_exists( $file ) )
                                {
                                        $this->newContent = file_get_contents( $file );
                                        $this->content = array_merge( $this->content, json_decode( $this->newContent, true ) );
                                }
                                }
                        }
        }
        else
        {
                return false;
        }
    }

        public function getContent()
        {
                return $this->content;
        }

        public function get( $needle )
        {
                if ( strpos( $needle, '.' ) )
                {
                        $childs = explode( '.', $needle );
                }
                else
                {
                        $childs[0] = $needle;
                }

                for ( $i = 0; $i < count( $childs ); $i++ )
                {
                        $child = $this->getChild( $childs[$i] );
                }

                $this->child = '';

                return $child;
        }

        private function getChild( $child )
        {
                $this->parent = ( $this->child ) ? $this->child : $this->content;
                if ( array_key_exists( $child, $this->parent ) )
                {
                        $this->child = $this->parent[$child];

                        return $this->child;
                }
        }

        public function getKeyByValue( $hash, $needle, $range = false )
        {
                $result = false;

                $needle = ( string ) $needle;

                foreach( $hash as $key => $value )
                {
                        if ( $range )
                        {
                                $commaFound = strpos( $value, ',' );
                                if ( $commaFound )
                                {
                                        $value = substr( $value, 0, $commaFound );
                                }
                                $rangeValue = explode( "-", $value );
                                $last = ( count( $rangeValue ) - 1 );
                                if ( $needle >= $rangeValue[0] && $needle <= $rangeValue[$last] )
                                {
                                        $result = $key;
                                        break;
                                }
                        }
                        else
                        {
                                $flatValue = explode( ",", $value );
                                if ( in_array( $needle, $flatValue ) )
                                {
                                        $result = $key;
                                        break;
                                }
                        }
                }

                return $result;
        }
}

?>