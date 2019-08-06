# phplist-plugin-repermission

Send repermission campaigns which require subscribers to re-confirm their list membership.

requires phpList version 3.0.3 or newer

This plugin will use the campaign system to send a one-off repermission campaign to your subscribers. After that, phpList will ensure 
further campaigns will only go out to subscribers who responded to the request.

It does this as follows:

  - in the Send-campaign page, on the Format tab, there will be an option (radio button) called "Repermission". Choose this option to send the campaign as a repermission campaign.
  - When you do so, you need to add [CONFIRMATIONURL] to the content. The confirmation URL will be the place where the recipients can confirm that they want to be in your system.
  - After sending the campaign, the subscriber will be marked "Blacklisted" in the phpList system, which means no further mails will be sent. HOWEVER:
  - The recipients who clicked the Confirmation URL will be removed from the blacklist and turn into normal subscribers.

## Advanced options

When the subcriber confirms, they will receive the standard "Welcome to our newsletter" message.

To send them a different message, do the following:

  - Create a subscribe page with the message and details you want to send
  - Set the "Settings" value for "Subscribe page for invitation responses" which will be added by this plugin

What will happen is the following:

- when the message is sent to this subscriber, their profile is updated to have the subscribe page set with the value from the config setting
- when they confirm their subscription, the welcome message from this subscribe page will be sent.

With the subscribe page option, you can set "Add subscribers confirming an invitation to this list" in settings
to automatically add subscribers who confirm to this list.

This requires using the "Subscribe page for invitation responses" setting, and the confirmation being made on that
subscribe page.
