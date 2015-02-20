<?php 
$members[0] = '                           <!--List 2 -->
                           <span class="lists-of-members"> 
<!-- member 1 -->
                            <div class="member-profile pull-left">
                                <img src="./template/img/developer_alimova.jpg" class="member-photo improve-img"> <br>
                                <span class="member-name"><a href="http://vk.com/cnucmumrik">Алимова Анна</a></span> <br>
                                <span class="member-school">МБОУ СОШ № 30</span> <br>
                                <span class="description-mini">Кружок "Электротехника"</span>
                            </div>  
 <!-- member 1 -->
 <!-- member 2 -->
                            <div class="member-profile pull-left">
                                <img src="./template/img/developer_marchenko.jpg" class="member-photo improve-img"> <br>
                                <span class="member-name"><a href="http://vk.com/id147199028">Марченко Марина</a></span> <br>
                                <span class="member-school">МБОУ СОШ № 2</span> <br>
                                <span class="description-mini">Кружок "Электротехника" </span>
                            </div> 
                            <!-- member 2 -->
 <!-- member 3 -->
                            <div class="member-profile pull-left">
                                <img src="./template/img/developer_dubrasov.jpg" class="member-photo improve-img"> <br>
                                <span class="member-name"><a href="http://vk.com/danildubrasov">Дубрасов Данил</a></span> <br>
                                <span class="member-school">Лицей № 3</span> <br>
                                <span class="description-mini">Кружок "Электротехника" </span>
                            </div> 
 <!-- member 3 -->            
                           </span>  
                           <!--List 2 -->';
                           
if(isset($_GET['num'])) {
    $data = $members[$_GET['num']];
	header("Content-type: text/txt; charset=UTF-8");
	echo ($_GET['num'] > count($members) - 2) ? "<script>document.isItLast = true;</script> $data" : $data;
}
 ?>