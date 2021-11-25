<?php

/**
 * Views: Template manager 
 * Page content and structure is managed with a seperate page object.
 *
 * @version 1.0
 * @author Michael Peacock
 */
class Template {

    private $page;

    /**
     * Data which has been prepared and then cached for later usage, primarily within the template engine
     */
    private $dataCache = array();

    /**
     * Include our page class, and build a page object to manage the content and structure of the page
     * @param Object our registry object
     */
    public function __construct(Registry $registry) {
        $this->registry = $registry;
        include( FRAMEWORK_PATH . '/Registry/page.class.php');
        $this->page = new Page($registry);
    }

    /**
     * Get data from the data cache
     * @param int data cache pointed
     * @return array the data
     */
    public function dataFromCache($cache_id) {
        //print_r($this->dataCache[$cache_id]);
        return $this->dataCache[$cache_id];
    }

    /**
     * Add a template bit from a view to our page
     * @param String $tag the tag where we insert the template e.g. {hello}
     * @param String $bit the template bit (path to file, or just the filename)
     * @return void
     */
    public function addTemplateBit($tag, $bit, $data = array()) {
        if (strpos($bit, 'Views/') === false) {
            $bit = 'Views/templates/' . $bit;
        }
        $this->page->addTemplateBit($tag, $bit, $data);
    }

    /**
     * Store some data in a cache for later
     * @param array the data
     * @return int the pointed to the array in the data cache
     */
    public function cacheData($data) {
        $this->dataCache[] = $data;
        //print_r($this->dataCache);
        return count($this->dataCache) - 1;
    }

    /**
     * Take the template bits from the view and insert them into our page content
     * Updates the pages content
     * @return void
     */
    private function replaceBits() {
        $bits = $this->page->getBits();
        // loop through template bits
        foreach ($bits as $tag => $template) {
            if (is_file($template['template'])) {
                $templateContent = file_get_contents($template['template']);
                $tags = array_keys($template['replacements']);
                $tagsNew = array();
                foreach ($tags as $taga) {
                    $tagsNew[] = '{' . $taga . '}';
                }
                $values = array_values($template['replacements']);
                $templateContent = str_replace($tagsNew, $values, $templateContent);
                $newContent = str_replace('{' . $tag . '}', $templateContent, $this->page->getContent());
                $this->page->setContent($newContent);
            }
        }
    }

    /**
     * Replace tags in our page with content
     * @return void
     */
    private function replaceTags($pp = false) {
        // get the tags in the page
        if ($pp == false) {
            $tags = $this->page->getTags();
        } else {
            $tags = $this->page->getPPTags();
        }

        // go through them all
        foreach ($tags as $tag => $data) {
            // if the tag is an array, then we need to do more than a simple find and replace!

            if (is_array($data)) {

                // it is some cached data...replace tags from cached data
                $this->replaceDataTags($tag, $data[1]);
            } else {
                // replace the content	    	
                $newContent = str_replace('{' . $tag . '}', $data, $this->page->getContent());
                // update the pages content
                $this->page->setContent($newContent);
            }
        }
    }

    /**
     * Replace content on the page with data from the database
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the queries ID in the query cache
     * @return void
     */
    private function replaceDBTags($tag, $cacheId) {
        $block = '';
        $blockOld = $this->page->getBlock($tag);
        $apd = $this->page->getAdditionalParsingData();
        $apdkeys = array_keys($apd);
        // foreach record relating to the query...
        while ($tags = $this->registry->getObject('db')->resultsFromCache($cacheId)) {
            $blockNew = $blockOld;

            // Do we have APD tags?
            if (in_array($tag, $apdkeys)) {
                // YES we do!
                foreach ($tags as $ntag => $data) {
                    $blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
                    // Is this tag the one with extra parsing to be done?
                    if (array_key_exists($ntag, $apd[$tag])) {
                        // YES it is
                        $extra = $apd[$tag][$ntag];
                        // does the tag equal the condition?
                        if ($data == $extra['condition']) {

                            // Yep! Replace the extratag with the data
                            $blockNew = str_replace("{" . $extra['tag'] . "}", $extra['data'], $blockNew);
                        } else {
                            // remove the extra tag - it aint used!
                            $blockNew = str_replace("{" . $extra['tag'] . "}", '', $blockNew);
                        }
                    }
                }
            } else {
                // create a new block of content with the results replaced into it
                foreach ($tags as $ntag => $data) {
                    $blockNew = str_replace("{" . $ntag . "}", $data, $blockNew);
                }
            }

            $block .= $blockNew;
        }
        $pageContent = $this->page->getContent();
        // remove the seperator in the template, cleaner HTML
        $newContent = str_replace('<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent);
        // update the page content
        $this->page->setContent($newContent);
    }

    /**
     * Replace content on the page with data from the cache
     * @param String $tag the tag defining the area of content
     * @param int $cacheId the datas ID in the data cache
     * @return void
     */
    private function replaceDataTags($tag, $cacheId) {

        $blockOld = $this->page->getBlock($tag);
        $block = '';
        $tags = $this->dataFromCache($cacheId);        

        foreach ($tags as $key => $tagsdata) {
            $blockNew = $blockOld;
            foreach ($tagsdata as $taga => $data) {
                $blockNew = str_replace("{" . $taga . "}", $data, $blockNew);
            }
            $block .= $blockNew;
        }


        $pageContent = $this->page->getContent();
        $newContent = str_replace('<!-- START ' . $tag . ' -->' . $blockOld . '<!-- END ' . $tag . ' -->', $block, $pageContent);
        $this->page->setContent($newContent);
    }

    /**
     * Get the page object
     * @return Object 
     */
    public function getPage() {
        return $this->page;
    }

    /**
     * Set the content of the page based on a number of templates
     * pass template file locations as individual arguments
     * @return void
     */
    public function buildFromTemplates() {
        $bits = func_get_args();
        //print_r($bits);
        $content = "";
        foreach ($bits as $bit) {

            if (strpos($bit, 'Views/') === false) {
                $bit = 'Views/templates/' . $bit;
            }
            if (file_exists($bit) == true) {
                $content .= file_get_contents($bit);
            }
        }
        $this->page->setContent($content);
    }

    /**
     * Convert an array of data into some tags
     * @param array the data 
     * @param string a prefix which is added to field name to create the tag name
     * @return void
     */
    public function dataToTags($data, $prefix) {
        foreach ($data as $key => $content) {
            $this->page->addTag($prefix . $key, $content);
        }
    }

    /**
     * Take the title we set in the page object, and insert them into the view
     */
    public function parseTitle() {
        $newContent = str_replace('<title>', '<title>' . $this->page->getTitle(), $this->page->getContent());
        $this->page->setContent($newContent);
    }

    /**
     * Parse the page object into some output
     * @return void
     */
    public function parseOutput() {
        $this->replaceBits();
        $this->replaceTags(false);
        $this->replaceBits();
        $this->replaceTags(true);
        $this->parseTitle();
    }

}

?>