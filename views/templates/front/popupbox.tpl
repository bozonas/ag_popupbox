<div class="home_popup" style="display: none">
  {if (isset($popupbox_data) || isset($popupbox_data_mobile)) && $mobile_device == false }
    {$popupbox_data}
  {else}
    {$popupbox_data_mobile}
  {/if}
  <input class="inputNew popup-btn" id="popup_newsletter-input" type="text" name="email" size="18" value="{if isset($value) && $value}{$value}{else}{l s='your e-mail' mod='blocknewsletter'}{/if}" />
  <input id="popup_subscribe_btn" value="ok" class="button_mini popup-btn" name="submitNewsletter" />
  <input id="popup_cancel_btn" value="ok" class="button_mini popup-btn" name="" />
</div>

<script type="text/javascript">
var isMobile="{$mobile_device}";
{literal}
  $(document).ready(function() { 
    var cookie = $.getCookie('home_popup');
    if (isMobile) {
      if (cookie != '1') {
        $.setCookie('home_popup','1',1);// expiration 1 hour
        $.fancybox({
                'content' 	: $(".home_popup").html(),
                'autoScale' : true
                });
      }
    } else {
      (function () {
      $("html").on("mouseout.ouibounce", function () {
        function e() {
          if (cookie != '2') {
            $.setCookie('home_popup','1',1);// expiration 1 hour
            $.fancybox({
              'content' 	: $(".home_popup").html(),
              'autoScale' : true
              });
          }
        }
        return function (t) {
          if (!(t.clientY < 20))
            return;
          e();
          $("html").off("mouseout.ouibounce")
        }
      }
        ())
    })();
    }

    $('#subscribe_popup_btn').click(function(e) {
        debugger;
        e.preventDefault();
        var mainurl = {/literal}"{$link->getPageLink('index', true)|escape:'html'}"{literal};
        var data = {
          email: $('#popup_newsletter-input'),
          submitNewsletter: "",
          action: 0
        };
        $.post(mainurl, data);
        $.fancybox.close();
        $.setCookie('home_popup','1',60 * 24); // 60 days
      });
  
    $('#popup_cancel_btn').click(function(e) {
        e.preventDefault();

        $.setCookie('home_popup','1',1 * 24);
        $.fancybox.close();
      });
  });
</script>
{/literal}