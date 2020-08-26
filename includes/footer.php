<div class="footer container-fluid">
    <p>Tomas &copy; <?php echo date('Y'); ?></p>
</div>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pushbar.js@1.0.0/src/pushbar.min.js"></script>
<script type="text/javascript">
    const pushbar = new Pushbar({
        blur:true,
        overlay:true,
    });
</script>
<script type="text/javascript" src="../static/js/pushbar.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    if ($(window).width() > 992) {
        $(window).scroll(function(){
            if ($(this).scrollTop() > 40) {
                $('#navbar_top').addClass("fixed-top");
                $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            }else{
                $('#navbar_top').removeClass("fixed-top");
                $('body').css('padding-top', '0');
            }
        });
    }
</script>