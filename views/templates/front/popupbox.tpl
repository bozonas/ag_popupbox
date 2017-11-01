<div class="home_popup" style="display: none">
  {if (isset($popupbox_data) || isset($popupbox_data_mobile)) && $mobile_device == false }
    {$popupbox_data}
  {else}
    {$popupbox_data_mobile}
  {/if}
  <div class="{if ($mobile_device == false)}{'not-mobile'}{else}{'mobile'}{/if}">
    <input class="inputNew popup-btn" id="popup_newsletter-input" type="text" name="email" size="18" placeholder="Įveskite el. paštą" />
    <button id="popup_subscribe_btn" class="button_mini popup-btn" name="submitNewsletter" onclick="popup_subscribeNewsLetter();">
      <span>Gauti 15% nuolaidą ></span>
    </button>
    <button id="popup_cancel_btn" class="button_mini popup-btn" onclick="popup_closefancybox();">
      <span>{if ($mobile_device == false)}{'Ne, ačiū, noriu mokėti visą sumą'}{else}{'Ne, ačiū'}{/if}</span>
    </button>
  </div>
</div>

<script type="text/javascript">
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
          if (cookie != '1') {
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
  });

  function popup_subscribeNewsLetter() {
        var mainurl = {/literal}"{$link->getPageLink('index', true)|escape:'html'}"{literal};
        var data = {
          email: $('.fancybox-inner #popup_newsletter-input').val(),
          submitNewsletter: "",
          action: 0
        };
        $.post(mainurl, data);
        $.fancybox.close();
        $.setCookie('home_popup','1',60 * 24); // 60 days
  }

  function popup_closefancybox() {
      $.setCookie('home_popup','1',1 * 24);
      $.fancybox.close();
  }
</script>
{/literal}