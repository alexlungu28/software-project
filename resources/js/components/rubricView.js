function Deleteqry(id,_this)
{
    if (confirm("Are you sure you want to delete this Row?")===true) {
        $(_this).closest('th').remove();
    }
    return false;
}
