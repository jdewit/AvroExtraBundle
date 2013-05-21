<?php

namespace Avro\ExtraBundle\Features\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Behat\Context\Step;

use Application\CoreBundle\Document\Vendor;
use Application\CoreBundle\Document\Address;
use Avro\RatingBundle\Document\Rating;

// Require 3rd-party libraries here:
   require_once 'PHPUnit/Autoload.php';
   require_once 'PHPUnit/Framework/Assert/Functions.php';

/**
 * Feature context.
 */
class FeatureContext extends MinkContext implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^I wait$/
     */
    public function iWait()
    {
        $this->getSession()->wait(1000);
    }

    /**
     * @Given /^I am logged in as an admin$/
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $container = $this->kernel->getContainer();
        if ($container->getParameter('test_admin_set') != true) {

            $username = $container->getParameter('test_admin_username');
            $password = $container->getParameter('test_admin_password');
            $email = $container->getParameter('test_admin_email');

            return array(
                new Step\Given("I am on \"/login\""),
                new Step\Then("I fill in \"username\" with \"$username\""),
                new Step\Then("I fill in \"password\" with \"$password\""),
                new Step\Then("I press \"Login\""),
            );

            $container->setParameter('test_admin_set', true);
        }
    }

    /**
     * @Given /^I am logged in as a user$/
     */
    public function iAmLoggedInAsAUser()
    {
        exec('sudo chmod 777 app/cache -R');
        exec('sudo chmod 777 app/logs -R');

        $container = $this->kernel->getContainer();
            if ($container->getParameter('test_user_set') != true) {

            $username = $container->getParameter('test_user_username');
            $password = $container->getParameter('test_user_password');

            return array(
                new Step\Given("I am on \"/login\""),
                new Step\Then("I fill in \"username\" with \"$username\""),
                new Step\Then("I fill in \"password\" with \"$password\""),
                new Step\Then("I press \"Login\""),
            );

            $container->setParameter('test_user_set', true);
        }
    }

    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function iClickOn($arg1)
    {
        $session = $this->getSession();
        $page = $session->getPage();
        $link = $page->findLink($arg1);
        if ($link) {
            $link->press();
        } else {
            throw new \Exception(sprintf('No link found called %s', $arg1));
        }
    }

    /**
     * @Then /^get those markets$/
     */
    public function getThoseMarkets()
    {
        $container = $this->kernel->getContainer();
        $dm = $container->get('doctrine.odm.mongodb.document_manager');
        $session = $this->getSession();
        $page = $session->getPage();
        $rows = $page->findAll('css', '.marketlist tr.member');
        $hrefs = array();
        foreach($rows as $row) {
            $link = $row->find('css', 'a:not(.popup)');
            if ($link) {
                $hrefs[] = $link->getAttribute('href');
            }
        }

        foreach($hrefs as $href) {
            $session->visit('http://www.bcfarmersmarket.org/'.$href);
            $page = $session->getPage();
            $vendorName = $page->find('css', 'h1')->getText();

            $vendor = new Vendor();
            $vendor->setName($vendorName);
            $vendor->setRating(new Rating());
            $dm->persist($vendor);
            $dm->flush();
        }
    }

    /**
     * @Given /^I fill in the chosen input for "([^"]*)" with "([^"]*)"$/
     */
    public function iFillInTheChosenInputForWith($arg1, $arg2)
    {
        $container = $this->kernel->getContainer();
        $session = $this->getSession();
        $page = $session->getPage();
        $select = $page->findField($arg1);
        $citySelect = $select->getParent()->find('css', '.chzn-single');
        $citySelect->press();
        $cityInput = $page->find('css', '.chzn-search input');
        $cityInput->setValue($arg2);
        $this->iWait();

    }

    /**
     * @Given /^I select the chosen input for "([^"]*)" with "([^"]*)"$/
     */
    public function iSelectTheChosenInputForWith($arg1, $arg2)
    {
        $container = $this->kernel->getContainer();
        $session = $this->getSession();
        $page = $session->getPage();
        $chosenResults = $page->findAll('css', '.chzn-results li');
        foreach ($chosenResults as $result) {
            if ($result->getText() == $arg2) {
                $result->click();

                break;
            }
        }
    }


    /**
     * @Given /^I fill in the select2 ajax input for "([^"]*)" with "([^"]*)" and select "([^"]*)"$/
     */
    public function iFillInTheSelectAjaxInputForWithAndSelect($arg1, $arg2, $arg3)
    {
        $container = $this->kernel->getContainer();
        $session = $this->getSession();
        $page = $session->getPage();
        $label = $this->getSession()->getPage()->find('xpath', '//label[text()="'. $arg1.'"]');
        if (!$label) {
            throw new \Exception(sprintf('No label with text %s', $arg1));
        }
        $choice = $label->getParent()->find('css', '.select2-choice');

        if (!$choice) {
            throw new \Exception('No select2 choice found');
        }
        $choice->press();
        $input = $page->find('css', '.select2-input');
        if (!$input) {
            throw new \Exception('No input found');
        }
        $input->setValue($arg2);
        $this->iWait();

        $chosenResults = $page->findAll('css', '.select2-results li');
        foreach ($chosenResults as $result) {
            if ($result->getText() == $arg3) {
                $result->click();
                break;
            }
        }
    }

    /**
     * @Given /^I fill in "([^"]*)" with parameter "([^"]*)"$/
     */
    public function iFillInWithParameter($arg1, $arg2)
    {
        $container = $this->kernel->getContainer();
        $param = $container->getParameter($arg2);
        $session = $this->getSession();
        $page = $session->getPage();
        $field = $page->findField($arg1);
        $field->setValue($param);
    }

    /**
     * @Given /^there is no user "([^"]*)"$/
     */
    public function thereIsNoUser($arg1)
    {
        $container = $this->kernel->getContainer();
        $dm = $container->get('doctrine.odm.mongodb.document_manager');

        $user = $dm->getRepository('ApplicationCoreBundle:User')->findOneBy(array('username' => $arg1));

        if ($user) {
            $dm->remove($user);
            $dm->flush();
        }
    }

    /**
     * @Then /^activate the account "([^"]*)"$/
     */
    public function activateTheAccount($arg1)
    {
        $container = $this->kernel->getContainer();
        $dm = $container->get('doctrine.odm.mongodb.document_manager');

        $user = $dm->getRepository('ApplicationCoreBundle:User')->findOneBy(array('username' => $arg1));

        if ($user) {
            $user->setEnabled(true);
            $dm->persist($user);
            $dm->flush();
        }

    }


    /**
     * @Given /^I make sure Stripe is synced$/
     */
    public function iMakeSureStripeIsSynced()
    {
        throw new PendingException();
    }

    /**
     * @Given /^I clear my settings$/
     */
    public function iClearMySettings()
    {
        $container = $this->kernel->getContainer();
        $dm = $container->get('doctrine.odm.mongodb.document_manager');

        $user = $dm->getRepository('ApplicationCoreBundle:User')->findOneBy(array('username' => 'admin'));
        $user->setStripeReference(false);
        $user->setHasStripeConnect(false);
        $user->setStripeClientId(false);
        $user->setStripeAccessToken(false);
        $user->setStripeId(false);
        $dm->persist($user);
        $dm->flush();
    }


}
