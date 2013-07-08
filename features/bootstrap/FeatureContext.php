<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends Behat\MinkExtension\Context\MinkContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Then /^"([^"]*)" should be visible$/
     */
    public function shouldBeVisible($selector)
    {
        $element = $this->getSession()->getPage()->find('css', $selector);
        if (empty($element))
            throw new Exception('Element "'.$selector.'" not found');

        $display = $this->getSession()->evaluateScript(
            'jQuery("'.$selector.'").css("display")'
        );

        if ($display === 'none')
            throw new Exception('Element "'.$selector.'" is not visible');
    }

    /**
     * @Then /^"([^"]*)" should not be visible$/
     */
    public function shouldNotBeVisible($selector)
    {
        $element = $this->getSession()->getPage()->find('css', $selector);
        if (empty($element))
            throw new Exception('Element "'.$selector.'" not found');

        $display = $this->getSession()->evaluateScript(
            'jQuery("'.$selector.'").css("display")'
        );

        if ($display !== 'none')
            throw new Exception('Element "'.$selector.'" is visible');
    }

    /**
     * @Given /^there is no dummy data$/
     */
    public function thereIsNoDummyData()
    {
        DB::query(Database::DELETE, 'TRUNCATE TABLE `images`')->execute();
        DB::query(Database::DELETE, 'TRUNCATE TABLE `imagevotes`')->execute();
        DB::query(Database::DELETE, 'TRUNCATE TABLE `comments`')->execute();
        DB::query(Database::DELETE, 'TRUNCATE TABLE `commentvotes`')->execute();
    }

    /**
     * @Given /^I have an image with width "([^"]*)" and height "([^"]*)" in "([^"]*)"$/
     */
    public function iHaveAnImageWithWidthAndHeightIn($arg1, $arg2, $arg3)
    {
        $image = imagecreate($arg1, $arg2);
        imagecolorallocate($image, 0, 0, 0);
        imagepng($image, $arg3);
    }

    /**
     * @Given /^the "([^"]*)" element should display "([^"]*)"$/
     */
    public function theElementShouldDisplay($selector, $image_path)
    {
        $selector_image_path = $this->getSession()->evaluateScript(
            'jQuery("'.$selector.'").attr("src")'
        );

        if (md5_file(DOCROOT.substr($selector_image_path, strlen(URL::base()))) !== md5_file($image_path))
            throw new Exception('Element "'.$selector.'" displays "'.$selector_image_path.'" rather than "'.$image_path.'"');
    }

    /**
     * @Given /^the "([^"]*)" element should be "([^"]*)" by "([^"]*)" pixels$/
     */
    public function theElementShouldBeByPixels($selector, $width, $height)
    {
        $selector_image_path = $this->getSession()->evaluateScript(
            'jQuery("'.$selector.'").attr("src")'
        );

        list($selector_width, $selector_height, $type, $attr) = getimagesize(DOCROOT.substr($selector_image_path, strlen(URL::base())));

        if ($width != $selector_width OR $height != $selector_height)
            throw new Exception('Element "'.$selector.'" is '.$selector_width.'x'.$selector_height.' instead of '.$width.'x'.$height);
    }

    /**
     * @Given /^the "([^"]*)" background image should be "([^"]*)" by "([^"]*)" pixels$/
     */
    public function theBackgroundImageShouldBeByPixels($selector, $width, $height)
    {
        $selector_image_path = $this->getSession()->evaluateScript(
            'jQuery("'.$selector.'").css("background-image")'
        );

        list($selector_width, $selector_height, $type, $attr) = getimagesize(substr($selector_image_path, 4, -1));

        if ($width != $selector_width OR $height != $selector_height)
            throw new Exception('Element "'.$selector.'" is '.$selector_width.'x'.$selector_height.' instead of '.$width.'x'.$height);
    }

    /**
     * @Given /^there is dummy data loaded as follows:$/
     */
    public function thereIsDummyDataLoadedAsFollows(TableNode $table)
    {
        $this->thereIsNoDummyData();
        $rows = $table->getRows();
        foreach ($rows as $row)
        {
            DB::insert('images', array('id', 'name', 'file'))
                ->values(array($row[0], $row[1], $row[2]))
                ->execute();
        }
    }

    /**
     * @Given /^there are image votes as follows:$/
     */
    public function thereAreImageVotesAsFollows(TableNode $table)
    {
        $rows = $table->getRows();
        foreach ($rows as $row)
        {
            DB::insert('imagevotes', array('ip', 'image'))
                ->values(array($row[1], $row[0]))
                ->execute();
        }
    }

    /**
     * @Given /^there are comments loaded as follows:$/
     */
    public function thereAreCommentsLoadedAsFollows(TableNode $table)
    {
        $rows = $table->getRows();
        foreach ($rows as $row)
        {
            DB::insert('comments', array('message', 'image'))
                ->values(array($row[1], $row[0]))
                ->execute();
        }
    }

    /**
     * @Given /^there are comment votes as follows:$/
     */
    public function thereAreCommentVotesAsFollows(TableNode $table)
    {
        $rows = $table->getRows();
        foreach ($rows as $row)
        {
            DB::insert('commentvotes', array('ip', 'comment'))
                ->values(array($row[1], $row[0]))
                ->execute();
        }
    }

    /**
     * @When /^I follow the second comment vote link$/
     */
    public function iFollowTheSecondCommentVoteLink()
    {
        $display = $this->getSession()->evaluateScript(
            'jQuery("section.single ul li:nth-child(2) a").click()'
        );

    }
}
