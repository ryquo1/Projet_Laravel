<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Models\web\AcnMember;
use App\Models\web\AcnRanked;
use App\Models\web\AcnWorking;
use App\Models\web\AcnPrerogative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AcnMemberController extends Controller
{
    /**
     * view of the member's pofile
     * 
     * @return mixed view of the member's pofile
     */
    public static function getProfilePage(){
        return view("profile",["member"=>auth()->user()]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request a request to updating the roles of a member
     * @param number $memberNum the identification of a member
     * @return mixed the redirection to the manager's panel page
     */
    public static function updateRolesMember(Request $request, $memberNum) {
        $checkboxFields = array("security", "secretary", "pilot");
        $fieldsMappingToNameInDatabase = array("security" => "Sécurité de surface",
                                                "secretary" => "Secrétaire",
                                                "pilot" => "Pilote");
        foreach($checkboxFields as $field) {
            $nameInDatabase = $fieldsMappingToNameInDatabase[$field];
            if ($request->exists($field)) {
                AcnWorking::createUserRole($memberNum, $nameInDatabase);
            } else {
                AcnWorking::deleteUserRole($memberNum, $nameInDatabase);
            }
        }
        return redirect(route("managerPanel"));
    }

    public static function updateStatus($member_num){
        AcnMember::changeStatus($member_num);
        return redirect('members');
    }

    /**
     * Return a view to modify the profile of a member
     *
     * @param [int] $mem_num_member
     * @return view
     */
    public static function modifyForm($mem_num_member){

        $member = AcnMember::getMemberInfo($mem_num_member);
        $pricing = AcnMember::getPrincing();
        $prerogatives = AcnPrerogative::getAllPrerogatives();
        $memberPrerogative = AcnPrerogative::getPrerogLabel(AcnMember::getMemberMaxPriority($mem_num_member));
        return view('members_modification',["member" => $member[0],"pricing" => $pricing ,"memberPrerogative" => $memberPrerogative ,"prerogatives" => $prerogatives]);
    }

    /**
     * Return a view to modify the profile of a member
     *
     * @param [int] $mem_num_member
     * @return view
     */
    public static function modifyProfil(){

        $mem_num_member = auth()->user()->MEM_NUM_MEMBER;
        $member = AcnMember::getMemberInfo($mem_num_member);

        $prerogative = AcnPrerogative::getPrerogLabel(AcnMember::getMemberMaxPriority($mem_num_member));

        return view('profil_modification',["member" => $member[0],"prerogative"=>$prerogative]);
    }

    public static function profilUpdate(Request $request){

        AcnMember::updateMemberProfil(auth()->user()->MEM_NUM_MEMBER, $request -> memberName, $request -> memberSurname);

        return redirect(route('profil_page'));
    }

    public static function registerForm(){

        $pricing = AcnMember::getPrincing();
        $prerogations = AcnPrerogative::all();

        return view('members_registration',["pricing" => $pricing,"prerogations"=>$prerogations]);
    }

    /**
     * Check if the modifications are legal and update the data if they are, else go on an Exception page
     *
     * @param Request $request
     * @return mixed
     */
    static public function modify(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";
        
        $member = AcnMember::getMemberInfo($request->memberNum);
        $prerogMemLvl = AcnMember::getMemberMaxPriority($request->memberNum);
        $preroglabel = AcnPrerogative::getPrerogLabel($request->memberPrerogPriority);

        //Checks if the secretary attempt to dicrease the number of remaining dive.
        if ($request -> remainingDive < $member[0]->MEM_REMAINING_DIVES) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des plongées à un adhérent, ne descendez pas en dessous de ".$member[0]->MEM_REMAINING_DIVES." plongées restante.;";
        }
        if ($request->pricingType == 'enfant' && $request->memberPrerogPriority > 4) {
            $err = true;
            $strErr .= "- Pour un abonnement enfant les prérogatives disponibles sont uniquement : PB, PA, PO-12, PO-20 et vous avez choisis : ".$preroglabel.";";
        }
        if ($request->pricingType == 'adulte' && $request->memberPrerogPriority <= 4 ) {
            $err = true;
            $strErr .= "- Les prérogatives : PB, PA, PO-12, PO-20 sont disponible uniquement pour les enfants et vous avez choisis : ".$preroglabel.";";
        }if ($request->memberPrerogPriority < $prerogMemLvl && $prerogMemLvl != 13) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des prérogatives à un adhérents, vous avez saisi un niveau de prérogative inférieur au dernier qu'il possède;";
        }if ($request->memberPrerogPriority < 8 && $prerogMemLvl == 13) {
            $err = true;
            $strErr .= "- Vous ne pouvez pas retirer des prérogatives à un adhérents, vous avez saisi un niveau de prérogative inférieur au dernier qu'il possède;";
        }


        if ($err) {
            $arrayErr = explode(";",$strErr);
            return view('memberException',['member_num'=>$request->memberNum,'error_msg'=>$arrayErr,'actionType'=>'Modification']);
        }
        else {
            AcnMember::updateMemberInfos($request);

                //search for every prerogation a member don't have and are below the prerogation selected, meant to add them later (for the 3 request below)
            if($request->pricingType == 'adulte'){
                if($request->memberPrerogPriority  == 13){
                    $pre = AcnRanked::getAllPRevPrerogativeButE1($request->memberNum,$request->memberPrerogPriority);

                }else{
                    $pre = AcnRanked::getAllPRevPrerogativeNotE1($request->memberNum,$request->memberPrerogPriority);
                }
            }else{
                //same but for children
                $pre = AcnRanked::getAllPRevPrerogativeChildren($request -> memberNum,$request->memberPrerogPriority);
            }

        //insert All the prerogative selected
        AcnRanked::insertAllPrerogative($pre,$request->memberNum);

            return redirect('members');
        }

    }

    static public function register(Request $request) {

        //creation of the error variable
        $err = false;
        //ceation of the error message
        $strErr = "";

        $preroglabel = AcnPrerogative::getPrerogLabel($request->member_prerog);
        $newNumMember = AcnMember::getNewNumMember();


        if ($request->pricing_type == 'enfant' && $request->member_prerog > 4) {
            $err = true;
            $strErr .= "- Pour un abonnement enfant les prérogatives disponibles sont uniquement : PB, PA, PO-12, PO-20 et vous avez choisis : ".$preroglabel.";";
        }
        if ($request->pricing_type == 'adulte' && $request->member_prerog <= 4 ) {
            $err = true;
            $strErr .= "- Les prérogatives : PB, PA, PO-12, PO-20 sont disponible uniquement pour les enfants et vous avez choisis : ".$preroglabel.";";
        }

        if ($err) {
            $arrayErr = explode(";",$strErr);
            return view('memberException',['member_num'=>$newNumMember,'error_msg'=>$arrayErr,'actionType'=>'Registration']);
        }
        else {
            AcnMember::insertNewMember($request,$newNumMember);

            //search for every prerogative a member need to have before the prerogation selected, meant to add every prerogative needed later (same for the 2 request below)
        if($request->pricing_type == 'adulte'){
            if($request->member_prerog  == 13){
                $pre = AcnRanked::getAllPRevPrerogativeButE1($newNumMember,$request->member_prerog);

            }else{
                $pre = AcnRanked::getAllPRevPrerogativeNotE1($newNumMember,$request->member_prerog);
            }
        }else{
            //same but for children
            $pre = AcnRanked::getAllPRevPrerogativeChildren($newNumMember,$request->member_prerog);
        }

        //insert All the prerogative selected
        AcnRanked::insertAllPrerogative($pre,$newNumMember);

            return redirect('members');
        }

    }

    static public function secretary() {
        $members = AcnMember::all();
        foreach($members as $member) {
            $member -> PRE_LABEL = AcnPrerogative::getPrerogLabel(AcnMember::getMemberMaxPriority($member -> MEM_NUM_MEMBER));
        }
        return view('members', ["name" => auth()->user()->MEM_NAME, "surname" => auth()->user()->MEM_SURNAME, "function" => auth()->user()->FUN_LABEL, "members" => $members]);
    }


}
