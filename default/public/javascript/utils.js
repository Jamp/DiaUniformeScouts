jQuery('.upper').on('keyup', function(){
    jQuery(this).val( this.value.toUpperCase() );
});
jQuery('.capitalize').on('keyup', function(){
    jQuery(this).val( this.value.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) );
});
function arraySearch(arr,val) {
    for (var i=0; i<arr.length; i++) {
        if (arr[i] == val)
            return true;
    }
    return false;
}