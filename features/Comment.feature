Feature: Comment
    In order to socialise on the site
    As a guest
    I need to be able to leave comments

    Scenario: View comments on an existing building
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
            | 43 | Bar | /tmp/bar.png |
        And there are comments loaded as follows:
            | 42 | Foo    |
            | 42 | Bar    |
            | 42 | Baz    |
            | 43 | Foobar |
        And I am on "view/42"
        Then the "section.single ul" element should contain "Foo"
        And I should see "Bar"
        And I should see "Baz"
        And I should not see "Foobar"
        When I am on "view/43"
        Then the "section.single ul" element should contain "Foobar"

    Scenario: Add comments on an existing building
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
        And I am on "view/42"
        When I fill in the following:
            | message | My comment |
        And I press "Add"
        Then I should be on "view/42"
        And the "section.single ul" element should contain "My comment"

    Scenario: Attempt to delete comment with invalid password
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
        And there are comments loaded as follows:
            | 42 | Bar |
        And I am on "view/42"
        Then I should see "Bar"
        When I am on "comment/delete/1/badpassword"
        And I am on "view/42"
        Then I should see "Bar"

    Scenario: Delete comment on an existing building
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
        And there are comments loaded as follows:
            | 42 | Bar |
        And I am on "view/42"
        Then I should see "Bar"
        When I am on "comment/delete/1/password"
        And I am on "view/42"
        Then I should not see "Bar"

    @mink:sahi
    Scenario: Vote on a comment
        Given there is dummy data loaded as follows:
            | 42 | Foo | /tmp/foo.png |
        And there are comments loaded as follows:
            | 42 | Bar |
            | 42 | Baz |
        And I am on "view/42"
        Then the "section.single ul li:nth-child(1)" element should contain "Bar"
        And the "section.single ul li:nth-child(2)" element should contain "Baz"
        When I follow the second comment vote link
        And I am on "view/42"
        Then the "section.single ul li:nth-child(1)" element should contain "Baz"
        And the "section.single ul li:nth-child(2)" element should contain "Bar"
