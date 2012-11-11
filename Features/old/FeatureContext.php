<?php

namespace Avro\AssetBundle\Features\Context;

use Behat\BehatBundle\Context\MinkContext;
use Behat\Behat\Context\ClosuredContextInterface;
use Behat\Behat\Context\TranslatedContextInterface;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;
use Behat\Behat\Event\SuiteEvent;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Feature context.
 */
class FeatureContext extends MinkContext {

    public function __construct($parameters)
    {
        parent::__construct($parameters); 
        
    }

    /**
     * @Given /^I delete the database$/
     */
    public function iDeleteTheDatabase()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropDatabase();
    }

    /**
     * @Given /^I create the database$/
     */
    public function iCreateTheDatabase()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $tool->createSchema($metadatas);
    }

    /**
     * @Then /^I should have no users$/
     */
    public function iShouldHaveNoUsers()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $userManager = $this->getContainer()->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        assertEmpty($users);
    }

    /**
     * @Given /^I am logged in as a user$/
     */
    public function iAmLoggedInAsAUser()
    {
        return array(
            new Step\Given("I am on \"/login\""),
            new Step\When("I fill in \"Email\" with \"joris.w.dewit@gmail.com\""),
            new Step\When("I fill in \"Password\" with \"password\""),
            new Step\When("I press \"Sign In\"")
        );
    }

    /**
     * @Given /^I am logged in as an admin$/
     */
    public function iAmLoggedInAsAnAdmin()
    {
        return array(
            new Step\Given("I am on \"/login\""),
            new Step\When("I fill in \"email\" with \"joris.w.dewit@gmail.com\""),
            new Step\When("I fill in \"Password\" with \"password\""),
            new Step\When("I press \"Sign In\"")
        );
    }

    /**
     * @Given /^"([^"]*)" is upgraded to an admin$/
     */
    public function isUpgradedToAnAdmin($email)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $user = $this->getContainer()->get('application_user.user_manager')->findUserByEmail($email);
        $user->addRole('ROLE_ADMIN');
        $em->persist($user);
        $em->flush();
    }

    /**
     * @Then /^"([^"]*)" should have the role "([^"]*)"$/
     */
    public function shouldHaveTheRole($email, $role)
    {
        $user = $this->getContainer()->get('fos_user.user_manager')->findUserByEmail($email);
        assertTrue($user->hasRole($role));
    }

    public function jqueryWait($duration)
    {
        $this->getSession()->wait($duration, '(0 === jQuery.active && 0 === jQuery(\':animated\').length)');
    }

    /**
     * @Given /^I wait for ajax request$/
     */
    public function iWaitForAjaxRequest()
    {
        $this->jqueryWait(20000);
    }

    /**
     * @Then /^I should see the alert "([^"]*)"$/
     */
    public function iShouldSeeTheAlert($notice)
    {
         $this->jqueryWait(20000);
         $this->assertElementContainsText('.alert', $notice);
    }

    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function iClickOn($id)
    {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $el = $page->find('css', '#'.$id);

        // get tag name:
        //echo $el->getTagName();

        // get element's href attribute:
        $el->click();
    }

    /**
     * @Then /^"([^"]*)" select should contain "([^"]*)" option$/
     */
    public function selectShouldContainOption($select, $option)
    {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $selectElement = $page->find('named', array('select', "\"{$select}\""));
        echo $selectElement->getTagName();
        $optionElement = $selectElement->find('value', array('option', "\"{$option}\""));
        echo $optionElement->getTagName();

        if ($optionElement) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @Then /^"([^"]*)" in "([^"]*)" should be selected$/
     */
    public function inShouldBeSelected($optionText, $select) {
        $page = $this->getMink()->getSession('sahi')->getPage();
        //$selectElement = $page->findField($select);
        $optionElement = $page->find('xpath', '//select/option[normalize-space(.)="'.$optionText.'"]');
        //it should have the attribute selected and it should be set to selected
        assertTrue($optionElement->hasAttribute("selected"));
        assertTrue($optionElement->getAttribute("selected") == "selected");
    }

    /**
     * @Then /^"([^"]*)" in "([^"]*)" should not be selected$/
     */
    public function inShouldNotBeSelected($optionValue, $select) {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $selectElement = $page->findField($select);
        $optionElement = $selectElement->find('named', array('option', "\"{$optionValue}\""));
        //it should have the attribute selected and it should be set to selected
        assertFalse($optionElement->hasAttribute("selected"));
    }

    /**
     * @Then /^"([^"]*)" should be visible$/
     */
    public function shouldBeVisible($selector) {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $el = $page->find('css', $selector);
        $style = '';
        if(!empty($el)){
            $style = preg_replace('/\s/', '', $el->getAttribute('style'));
        } else {
            throw new Exception("Element ({$selector}) not found");
        }

        assertFalse(false !== strstr($style, 'display:none'));
    }

    /**
     * @Then /^"([^"]*)" should not be visible$/
     */
    public function shouldNotBeVisible($selector) {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $el = $page->find('css', $selector);
        $style = '';
        if(!empty($el)){
            $style = preg_replace('/\s/', '', $el->getAttribute('style'));
        } else {
            throw new Exception("Element ({$selector}) not found");
        }

        assertTrue(false !== strstr($style, 'display:none'));
    }

    /**
     * @Given /^I edit the first table item$/
     */
    public function iEditTheFirstTableItem()
    {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $el = $page->find('css', 'tbody tr a.btn-edit');
        if ($el) {
            $el->click();
        } else { 
            return false;
        }
    }

    /**
     * @Given /^I press all "([^"]*)"$/
     */
    public function iPressAll($name)
    {
        $page = $this->getMink()->getSession('sahi')->getPage();
        $buttons = $page->findAll('named', array('button', "\"{$name}\""));
        foreach ($buttons as $button) {
            $button->press();
        }
    }
 

}
