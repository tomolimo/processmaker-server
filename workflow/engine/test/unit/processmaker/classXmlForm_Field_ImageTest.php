<?php
  $unitFilename = $_SERVER['PWD'] . '/test/bootstrap/unit.php' ;
  require_once( $unitFilename );

  require_once( PATH_THIRDPARTY . '/lime/lime.php');
  require_once( PATH_THIRDPARTY.'lime/yaml.class.php');
 
  G::LoadThirdParty('smarty/libs','Smarty.class');
  G::LoadSystem ( 'xmlform');
  G::LoadSystem ( 'xmlDocument');
  G::LoadSystem ( 'form');
  require_once( 'propel/Propel.php' );
  require_once ( "creole/Creole.php" );
  require_once (  PATH_CORE . "config/databases.php");  

  G::LoadClass ( 'xmlfield_Image');


//  $obj = new XmlForm_Field_Image ($dbc);
  $t   = new lime_test( 42, new lime_output_color() );

  $className = "XmlForm_Field_Image";
  $className = strtolower ( substr ($className, 0,1) ) . substr ($className, 1 );

  $reflect = new ReflectionClass( $className );
	$method = array ( );
	$testItems = 0;
 
  foreach ( $reflect->getMethods() as $reflectmethod )  {  
  	$params = '';
  	foreach ( $reflectmethod->getParameters() as $key => $row )   {  
  	  if ( $params != '' ) $params .= ', ';
  	  $params .= '$' . $row->name;  
  	}

 		$testItems++;
  	$methods[ $reflectmethod->getName() ] = $params;
  }
  //To change the case only the first letter of each word, TIA
  $className = ucwords($className);
  $t->diag("class $className" );

  //$t->isa_ok( $obj  , $className,  "class $className created");

  $t->is( count($methods) , 20,  "class $className have " . 20 . ' methods.' );
  // Methods
  $aMethods = array_keys ( $methods );
   //checking method 'render'
  $t->is ( $aMethods[0],      'render',   'render() is callable' );

  //$result = $obj->render ( $value, $owner);
  //$t->isa_ok( $result,      'NULL',   'call to method render ');
  $t->todo( "call to method render using $value, $owner ");


  //checking method 'XmlForm_Field'
  $t->is ( $aMethods[1],      'XmlForm_Field',   'XmlForm_Field() is callable' );

  //$result = $obj->XmlForm_Field ( $xmlNode, $lang, $home, $owner);
  //$t->isa_ok( $result,      'NULL',   'call to method XmlForm_Field ');
  $t->todo( "call to method XmlForm_Field using $xmlNode, $lang, $home, $owner ");


  //checking method 'validateValue'
  $t->is ( $aMethods[2],      'validateValue',   'validateValue() is callable' );

  //$result = $obj->validateValue ( $value);
  //$t->isa_ok( $result,      'NULL',   'call to method validateValue ');
  $t->todo( "call to method validateValue using $value ");


  //checking method 'executeXmlDB'
  $t->is ( $aMethods[3],      'executeXmlDB',   'executeXmlDB() is callable' );

  //$result = $obj->executeXmlDB ( $owner, $row);
  //$t->isa_ok( $result,      'NULL',   'call to method executeXmlDB ');
  $t->todo( "call to method executeXmlDB using $owner, $row ");


  //checking method 'executePropel'
  $t->is ( $aMethods[4],      'executePropel',   'executePropel() is callable' );

  //$result = $obj->executePropel ( $owner, $row);
  //$t->isa_ok( $result,      'NULL',   'call to method executePropel ');
  $t->todo( "call to method executePropel using $owner, $row ");


  //checking method 'executeSQL'
  $t->is ( $aMethods[5],      'executeSQL',   'executeSQL() is callable' );

  //$result = $obj->executeSQL ( $owner, $row);
  //$t->isa_ok( $result,      'NULL',   'call to method executeSQL ');
  $t->todo( "call to method executeSQL using $owner, $row ");


  //checking method 'htmlentities'
  $t->is ( $aMethods[6],      'htmlentities',   'htmlentities() is callable' );

  //$result = $obj->htmlentities ( $value, $flags, $encoding);
  //$t->isa_ok( $result,      'NULL',   'call to method htmlentities ');
  $t->todo( "call to method htmlentities using $value, $flags, $encoding ");


  //checking method 'renderGrid'
  $t->is ( $aMethods[7],      'renderGrid',   'renderGrid() is callable' );

  //$result = $obj->renderGrid ( $values, $owner, $onlyValue, $therow);
  //$t->isa_ok( $result,      'NULL',   'call to method renderGrid ');
  $t->todo( "call to method renderGrid using $values, $owner, $onlyValue, $therow ");


  //checking method 'renderTable'
  $t->is ( $aMethods[8],      'renderTable',   'renderTable() is callable' );

  //$result = $obj->renderTable ( $values, $owner, $onlyValue);
  //$t->isa_ok( $result,      'NULL',   'call to method renderTable ');
  $t->todo( "call to method renderTable using $values, $owner, $onlyValue ");


  //checking method 'dependentOf'
  $t->is ( $aMethods[9],      'dependentOf',   'dependentOf() is callable' );

  //$result = $obj->dependentOf ( );
  //$t->isa_ok( $result,      'NULL',   'call to method dependentOf ');
  $t->todo( "call to method dependentOf using  ");


  //checking method 'mask'
  $t->is ( $aMethods[10],      'mask',   'mask() is callable' );

  //$result = $obj->mask ( $format, $value);
  //$t->isa_ok( $result,      'NULL',   'call to method mask ');
  $t->todo( "call to method mask using $format, $value ");


  //checking method 'getAttributes'
  $t->is ( $aMethods[11],      'getAttributes',   'getAttributes() is callable' );

  //$result = $obj->getAttributes ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getAttributes ');
  $t->todo( "call to method getAttributes using  ");


  //checking method 'getEvents'
  $t->is ( $aMethods[12],      'getEvents',   'getEvents() is callable' );

  //$result = $obj->getEvents ( );
  //$t->isa_ok( $result,      'NULL',   'call to method getEvents ');
  $t->todo( "call to method getEvents using  ");


  //checking method 'attachEvents'
  $t->is ( $aMethods[13],      'attachEvents',   'attachEvents() is callable' );

  //$result = $obj->attachEvents ( $elementRef);
  //$t->isa_ok( $result,      'NULL',   'call to method attachEvents ');
  $t->todo( "call to method attachEvents using $elementRef ");


  //checking method 'createXmlNode'
  $t->is ( $aMethods[14],      'createXmlNode',   'createXmlNode() is callable' );

  //$result = $obj->createXmlNode ( $includeDefaultValues);
  //$t->isa_ok( $result,      'NULL',   'call to method createXmlNode ');
  $t->todo( "call to method createXmlNode using $includeDefaultValues ");


  //checking method 'updateXmlNode'
  $t->is ( $aMethods[15],      'updateXmlNode',   'updateXmlNode() is callable' );

  //$result = $obj->updateXmlNode ( $node, $includeDefaultValues);
  //$t->isa_ok( $result,      'NULL',   'call to method updateXmlNode ');
  $t->todo( "call to method updateXmlNode using $node, $includeDefaultValues ");


  //checking method 'getXmlAttributes'
  $t->is ( $aMethods[16],      'getXmlAttributes',   'getXmlAttributes() is callable' );

  //$result = $obj->getXmlAttributes ( $includeDefaultValues);
  //$t->isa_ok( $result,      'NULL',   'call to method getXmlAttributes ');
  $t->todo( "call to method getXmlAttributes using $includeDefaultValues ");


  //checking method 'maskValue'
  $t->is ( $aMethods[17],      'maskValue',   'maskValue() is callable' );

  //$result = $obj->maskValue ( $value, $owner);
  //$t->isa_ok( $result,      'NULL',   'call to method maskValue ');
  $t->todo( "call to method maskValue using $value, $owner ");


  //checking method 'cloneObject'
  $t->is ( $aMethods[18],      'cloneObject',   'cloneObject() is callable' );

  //$result = $obj->cloneObject ( );
  //$t->isa_ok( $result,      'NULL',   'call to method cloneObject ');
  $t->todo( "call to method cloneObject using  ");


  //checking method 'getPMTableValue'
  $t->is ( $aMethods[19],      'getPMTableValue',   'getPMTableValue() is callable' );

  //$result = $obj->getPMTableValue ( $oOwner);
  //$t->isa_ok( $result,      'NULL',   'call to method getPMTableValue ');
  $t->todo( "call to method getPMTableValue using $oOwner ");



  $t->todo (  'review all pendings methods in this class');
