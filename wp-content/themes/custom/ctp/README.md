# Hi, developers!

At one point, we had an Outlook 365 button on the `results-no-recall.php`
partial. It used to look like this:

```
<li class="add-to-calendar__ics">
    <form method="post" action="<?= get_template_directory_uri(); ?>/includes/_outlook-signin.php">
        <input type="hidden" name="days" value="<?= $add_to_calendar['days']; ?>. ">
        <input type="hidden" name="description" value="<?= $description; ?>. ">
        <input type="hidden" name="title" value="<?= $title; ?>">
        <input type="submit" value="Outlook 365">
    </form>
</li>
```

If it ever needs to be re-added, just insert this list item.
