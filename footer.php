<?php
/**
 * Created by PhpStorm.
 * User: ryagudin
 * Date: 8/9/14
 * Time: 3:12 PM
 */
?>

<div class="footer">
    <p>
        <a href="http://www.tufts.edu" class="footerlink">Tufts</a> |
        <a href="http://inside.tufts.edu/"  class="footerlink">InsideTufts</a> |
        <a href="http://www.tufts.edu/home/visiting_directions/"  class="footerlink">Directions</a> |
        <a href="http://whitepages.tufts.edu/"  class="footerlink">Find People</a>
        <br>
        Copyright &copy; <?php echo date("Y"); ?> <a href="http://www.tufts.edu/" class="footerlink">Tufts University</a>
    </p>
</div><!-- end .footer -->
<div class="clear"></div>
</div><!-- end #main-wrapper -->
<?php wp_footer(); ?>
<script language="javaScript" type="text/javascript" src='<?php bloginfo('template_directory')?>/js/main.js'></script>
</body>
</html>