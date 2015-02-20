	   <footer class="shadow-z-3-up white-text" >
        <div class="container">
            <div class="row">
                <div class="col-lg-10">
                        <div class="copyright-img">
                        <a href="http://www.mpei.ru/"><img src="http://upload.wikimedia.org/wikipedia/ru/1/1f/Logo_MPEI.jpg"></a>
                            <a href="http://rushydro.ru/"><img src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/img/rusgidro.png" ></a>
                            <a href="http://volpi.ru/"><img src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/img/bf30879d02d6536be966df1620bbed8f_100x73.png"></a>
                            
                        </div>
                    <div class="copyright  clearfix"><p class="pull-right">Copyright © 2013-2015 ОАО «РусГидро»</p></div>
                </div>
            </div>
        </div>
    </footer>
    <div class="navbar navbar-material-deeppurple mec-menu shadow-z-3">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/">МЭК</a>
  </div>
  <div class="navbar-collapse collapse navbar-responsive-collapse">
    <ul class="nav navbar-nav">
      <li ><a href="http://<?php echo $_SERVER['SERVER_NAME'] ?>/#about-us">О нас</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
    	<li>
    			  <div class="radio radio-success white-text  mec-menu-status" id="mecStatus">
                  <label>
                  	<?php include "./function.php"; echo onlinemec();?>
                    <input type="radio" name="sample1" value="option1"  disabled="true"><span class="circle"></span><span class="check"></span>
                  </label>
                </div>
    	</li>
    	<li>
    		<a href="">
    		</a>
    	</li>
    </ul>
  </div>
</div>

    <!--!-->


<div class="floating-btn">
	<button class="btn btn-fab btn-raised btn-info" data-toggle="modal" data-target="#complete-dialog"><i class="mdi-action-grade"></i></button>
</div>
<div id="complete-dialog" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Мэк в ваш дом!</h4>
      </div>
      <div class="modal-body">
        <p>Много много настроек или мало</p>
		  <h2>Крутость МЭК</h2>
		  <div class="slider shor slider-material-orange"></div>

		  <h2>Температура МЭК</h2>
		  <div class="slider shor slider-material-orange"></div>

		  <h2>Громкость музыки</h2>
		  <div class="slider shor slider-material-orange"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-dismiss="modal"><i class="mdi-action-shopping-cart"></i>Дайте два!</button>
        <button class="btn btn-info" data-dismiss="modal"><i class="mdi-navigation-check"></i>Ок</button>
      </div>
    </div>
  </div>
</div>

            <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script>
            (function(){

                var $button = $("<div id='source-button' class='btn btn-primary btn-xs'>&lt; &gt;</div>").click(function(){
                    var index =  $('.bs-component').index( $(this).parent() );
                    $.get(window.location.href, function(data){
                        var html = $(data).find('.bs-component').eq(index).html();
                        html = cleanSource(html);
                        $("#source-modal pre").text(html);
                        $("#source-modal").modal();
                    })

                });

                $('.bs-component [data-toggle="popover"]').popover();
                $('.bs-component [data-toggle="tooltip"]').tooltip();

                $(".bs-component").hover(function(){
                    $(this).append($button);
                    $button.show();
                }, function(){
                    $button.hide();
                });

                function cleanSource(html) {
                    var lines = html.split(/\n/);

                    lines.shift();
                    lines.splice(-1, 1);

                    var indentSize = lines[0].length - lines[0].trim().length,
                        re = new RegExp(" {" + indentSize + "}");

                    lines = lines.map(function(line){
                        if (line.match(re)) {
                            line = line.substring(indentSize);
                        }

                        return line;
                    });

                    lines = lines.join("\n");

                    return lines;
                }

                $(".icons-material .icon").each(function() {
                    $(this).after("<br><br><code>" + $(this).attr("class").replace("icon ", "") + "</code>");
                });

            })();

        </script>
        <script src="http://fezvrasta.github.io/snackbarjs/dist/snackbar.min.js">
        	        	$.snackbar({content: '123', style: 'toast'б timeout: '100'});
        </script>
        <script src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/dist/js/ripples.min.js"></script>
        <script src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/dist/js/material.min.js"></script>
        <script src="http://fezvrasta.github.io/snackbarjs/dist/snackbar.min.js"></script>
            <script src="http://<?php echo $_SERVER['SERVER_NAME'] ?>/template/js/main.js" type="text/javascript"></script>



        <script src="http://cdnjs.cloudflare.com/ajax/libs/noUiSlider/6.2.0/jquery.nouislider.min.js"></script>>
        <script>
            $(function() {
                $.material.init();
                $(".shor").noUiSlider({
                    start: 40,
                    connect: "lower",
                    range: {
                        min: 0,
                        max: 100
                    }
                });

                $(".svert").noUiSlider({
                    orientation: "vertical",
                    start: 40,
                    connect: "lower",
                    range: {
                        min: 0,
                        max: 100
                    }
                });
            });
        </script>
