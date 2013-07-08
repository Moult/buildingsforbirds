Feature: Image
    In order to use the site
    As a guest
    I need to be able to use the image system

    Scenario: Look at the homepage
        Given I am on the homepage
        Then I should see "Buildings for Birds" in the "header h1" element
        And I should see "Browse" in the "section#browse>h1" element
        And the "section#browse" element should contain "Add a render"

    Scenario: Browse existing renders
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        And there are image votes as follows:
            | 42 | 127.0.0.1 |
            | 42 | 127.0.0.2 |
            | 43 | 127.0.0.1 |
        And there are comments loaded as follows:
            | 42 | Foo    |
            | 42 | Bar    |
            | 42 | Baz    |
            | 43 | Foobar |
        And there are comment votes as follows:
            | 3 | 127.0.0.1  |
            | 2 | 127.0.0.2  |
            | 2 | 127.0.0.3  |
            | 4 | 127.0.0.4  |
            | 4 | 127.0.0.5  |
            | 4 | 127.0.0.6  |
            | 1 | 127.0.0.7  |
            | 1 | 127.0.0.8  |
            | 1 | 127.0.0.9  |
            | 1 | 127.0.0.10 |
        And I am on "view/42/"
        Then the "section#browse h2" element should contain "Foo"
        And the "section#browse div" element should contain "2"
        And the "section#browse ul li:nth-child(1)" element should contain "Foo"
        And the "section#browse ul li:nth-child(2)" element should contain "Bar"
        And the "section#browse ul li:nth-child(3)" element should contain "Baz"
        When I follow "View all"
        Then I should be on the homepage
        And the "section#browse>ul>li:nth-child(1) h2" element should contain "Foo"
        And the "section#browse>ul>li:nth-child(1) div" element should contain "2"
        And the "section#browse>ul>li:nth-child(1) ul li:nth-child(1)" element should contain "Foo"
        And the "section#browse>ul>li:nth-child(1) ul li:nth-child(2)" element should contain "Bar"
        And the "section#browse>ul>li:nth-child(1) ul li:nth-child(3)" element should contain "Baz"
        And the "section#browse>ul>li:nth-child(2) h2" element should contain "Bar"
        And the "section#browse>ul>li:nth-child(2) div" element should contain "1"
        And the "section#browse>ul>li:nth-child(2) ul li:nth-child(1)" element should contain "Foobar"
        When I follow "Bar"
        Then I should be on "view/43/"
        Then the "section#browse h2" element should contain "Bar"

    Scenario: Attempt to delete existing image without password
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        And I am on the homepage
        Then I should see "Foo"
        When I am on "image/delete/42/badpassword"
        And I go to the homepage
        Then I should see "Foo"

    Scenario: Delete existing image
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        And I am on the homepage
        Then I should see "Foo"
        When I am on "image/delete/42/password"
        And I go to the homepage
        Then I should not see "Foo"

    @mink:sahi
    Scenario: Vote on an existing render from the browse page
        Given I am on the homepage
        And there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        Then the "section#browse>ul>li:nth-child(1) div span" element should contain "0"
        When I follow "Love this building"
        Then the "section#browse>ul>li:nth-child(1) div span" element should contain "1"
        When I follow "Foo"
        Then the "section#browse div" element should contain "1"

    @mink:sahi
    Scenario: Vote on an existing render from the view page
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        And I am on "view/42/"
        Then the "section.single div" element should contain "0"
        When I follow "Love this building"
        Then the "section.single div" element should contain "1"

    @mink:sahi
    Scenario: Add and view a new render
        Given I am on the homepage
        And there is no dummy data
        And I have an image with width "326" and height "200" in "/tmp/building.jpg"
        Then "section#browse form" should not be visible
        When I follow "Add a render"
        Then "section#browse form" should be visible
        When I fill in "name" with "My Building"
        And I attach the file "/tmp/building.jpg" to "building"
        And I press "Add"
        Then the url should match ".*view/1"
        And the "header aside" element should contain "success"
        And I should see "View" in the "section#browse>h1" element
        And the "section#browse" element should contain "View all"
        And the "section#browse h2" element should contain "My Building"
        And the "section#browse div img" element should display "/tmp/building.jpg"
        And the "section#browse div img" element should be "326" by "200" pixels
        And the "section#browse div" background image should be "980" by "600" pixels

    @mink:sahi
    Scenario: Add and view a new oversized render
        Given I am on the homepage
        And there is no dummy data
        And I have an image with width "948" and height "400" in "/tmp/building.jpg"
        Then "section#browse form" should not be visible
        When I follow "Add a render"
        Then "section#browse form" should be visible
        When I fill in "name" with "My Building"
        And I attach the file "/tmp/building.jpg" to "building"
        And I press "Add"
        Then the url should match ".*view/1"
        And the "header aside" element should contain "success"
        And I should see "View" in the "section#browse>h1" element
        And the "section#browse" element should contain "View all"
        And the "section#browse h2" element should contain "My Building"
        And the "section#browse div img" element should be "474" by "200" pixels
        And the "section#browse div" background image should be "980" by "412" pixels

    Scenario: Attempt to add a nameless building
        Given I am on the homepage
        And there is no dummy data
        And I press "Add"
        Then I should be on the homepage
        And I should see "fail"

    Scenario: Attempt to add a building without an image
        Given I am on the homepage
        And there is no dummy data
        When I fill in "name" with "My Building"
        And I press "Add"
        Then I should be on the homepage
        And I should see "fail"
