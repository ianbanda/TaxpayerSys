<?php

class Aboutcontroller {
	
	/**
	 * Controller constructor - direct call to false when being embedded via another controller
	 * @param Registry $registry our registry
	 * @param bool $directCall - are we calling it directly via the framework (true), or via another controller (false)
	 */
	public function __construct( Registry $registry, $directCall )
	{
		$this->registry = $registry;
		
		$urlBits = $this->registry->getObject('url')->getURLBits();

                if( isset( $urlBits[1] ) )
		{
			switch( $urlBits[1] )
			{
				case 'vision':
					$this->createRelationship( intval( $urlBits[2] ) );
					break;	
				case 'contacs':
					$this->approveRelationship( intval( $urlBits[2] ) );
					break;
				
				default:
					break;
			}
			
		}
		else
		{
                    $this->registry->getObject('template')->buildFromTemplates( 'header.tpl.php', 'about/view.tpl.php', 'footer.tpl.php' );	
		}
		
	}
	
	
}


?>