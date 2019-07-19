<?php 
/**
#############################################################################################################
#                                    ###################################                                    #
#####                                ###################################                                #####
##########                            ############## ### ##############                            ##########
##########                            #############  ###  #############                            ##########
#############                          ############       ############                          #############
##############                            ########         ########                            ##############
###############                                                                               ###############
################                                                                             ################
#################                                                                           #################
#################                                                                           #################
#################                                                                           #################
#################                                                                           #################
#################                                                                           #################
#################                                                                           #################
################           #######                                         #######           ################
###############     ##################                                 ##################     ###############
##########################################                         ##########################################
#############################################                   #############################################
###############################################               ###############################################
#################################################           #################################################
##################################################         ##################################################
###################################################       ###################################################
####################################################     ####################################################
#####################################################   #####################################################
###################################################### ######################################################
#############################################################################################################
 */
class batman 
{
    
    function __construct()
    {
        
    }

    function user_b($arg=0){
        if($arg==1){
            echo "\n".str_repeat("#",109)."\n";usleep (25000); echo str_repeat("#",1).str_repeat(" ",36).str_repeat("#",17)."#".str_repeat("#",17).str_repeat(" ",36).str_repeat("#",1)."\n";usleep (25000); echo str_repeat("#",5).str_repeat(" ",32).str_repeat("#",17)."#".str_repeat("#",17).str_repeat(" ",32).str_repeat("#",5)."\n";usleep (25000); echo str_repeat("#",10).str_repeat(" ",28).str_repeat("#",14).str_repeat(" ",1)."###".str_repeat(" ",1).str_repeat("#",14).str_repeat(" ",28).str_repeat("#",10)."\n";usleep (25000); echo str_repeat("#",10).str_repeat(" ",28).str_repeat("#",13).str_repeat(" ",2)."###".str_repeat(" ",2).str_repeat("#",13).str_repeat(" ",28).str_repeat("#",10)."\n";usleep (25000); echo str_repeat("#",13).str_repeat(" ",26).str_repeat("#",12)."       ".str_repeat("#",12).str_repeat(" ",26).str_repeat("#",13)."\n";usleep (25000); echo str_repeat("#",14).str_repeat(" ",28).str_repeat("#",8)."         ".str_repeat("#",8).str_repeat(" ",28).str_repeat("#",14)."\n";usleep (25000); echo str_repeat("#",15).str_repeat(" ",39)." ".str_repeat(" ",39).str_repeat("#",15)."\n";usleep (25000); echo str_repeat("#",16).str_repeat(" ",38)." ".str_repeat(" ",38).str_repeat("#",16)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",17).str_repeat(" ",37)." ".str_repeat(" ",37).str_repeat("#",17)."\n";usleep (25000); echo str_repeat("#",16).str_repeat(" ",11).str_repeat("#",7).str_repeat(" ",20)." ".str_repeat(" ",20).str_repeat("#",7).str_repeat(" ",11).str_repeat("#",16)."\n";usleep (25000); echo str_repeat("#",15).str_repeat(" ",5).str_repeat("#",18).str_repeat(" ",16)." ".str_repeat(" ",16).str_repeat("#",18).str_repeat(" ",5).str_repeat("#",15)."\n";usleep (25000); echo str_repeat("#",42).str_repeat(" ",12)." ".str_repeat(" ",12).str_repeat("#",42)."\n";usleep (25000); echo str_repeat("#",45).str_repeat(" ",9)." ".str_repeat(" ",9).str_repeat("#",45)."\n";usleep (25000); echo str_repeat("#",47).str_repeat(" ",7)." ".str_repeat(" ",7).str_repeat("#",47)."\n";usleep (25000); echo str_repeat("#",49).str_repeat(" ",5)." ".str_repeat(" ",5).str_repeat("#",49)."\n";usleep (25000); echo str_repeat("#",50).str_repeat(" ",4)." ".str_repeat(" ",4).str_repeat("#",50)."\n";usleep (25000); echo str_repeat("#",51).str_repeat(" ",3)." ".str_repeat(" ",3).str_repeat("#",51)."\n";usleep (25000); echo str_repeat("#",52).str_repeat(" ",2)." ".str_repeat(" ",2).str_repeat("#",52)."\n";usleep (25000); echo str_repeat("#",53).str_repeat(" ",1)." ".str_repeat(" ",1).str_repeat("#",53)."\n";usleep (25000); echo str_repeat("#",54)." ".str_repeat("#",54)."\n";usleep (25000); echo str_repeat("#",109)."\n";usleep (25000);
        }
        if(empty(get_current_user())){
            echo "\n\033[36m".'Bienvenido, a continuación se ejecutará el proceso de informacion de campañas'."\033[0m"."\033[32m".'...'."\033[0m"."";

        }else{
            echo "\n\033[36m".'Bienvenido Sr '."\033[0m"."\033[32m".get_current_user()."\033[0m"."\033[36m".' a continuación se ejecutará el proceso de informacion de campañas'."\033[0m"."\033[32m".'...'."\033[0m"."";
        }
        sleep(2);
    }

}