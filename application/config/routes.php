<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
$route['404_override'] = 'error';


/*********** USER DEFINED ROUTES *******************/

$route['loginMe'] = 'login/loginMe';
$route['dashboard'] = 'user';
$route['logout'] = 'user/logout';
$route['userListing'] = 'user/userListing';
$route['userListing/(:num)'] = "user/userListing/$1";
$route['addNew'] = "user/addNew";

$route['addNewUser'] = "user/addNewUser";
$route['editOld'] = "user/editOld";
$route['editOld/(:num)'] = "user/editOld/$1";
$route['editUser'] = "user/editUser";
$route['deleteUser'] = "user/deleteUser";
$route['loadChangePass'] = "user/loadChangePass";
$route['changePassword'] = "user/changePassword";
$route['pageNotFound'] = "user/pageNotFound";
$route['checkEmailExists'] = "user/checkEmailExists";

$route['forgotPassword'] = "login/forgotPassword";
$route['resetPasswordUser'] = "login/resetPasswordUser";
$route['resetPasswordConfirmUser'] = "login/resetPasswordConfirmUser";
$route['resetPasswordConfirmUser/(:any)'] = "login/resetPasswordConfirmUser/$1";
$route['resetPasswordConfirmUser/(:any)/(:any)'] = "login/resetPasswordConfirmUser/$1/$2";
$route['createPasswordUser'] = "login/createPasswordUser";

/* End of file routes.php */
/* Location: ./application/config/routes.php */


// All Driver Routing

$route['transferMoneyToDriver'] = "AllDriverListing/transferMoneyToDriver";
$route['DriversListing'] = "AllDriverListing/DriversListing";
$route['UsersListing'] = "AllUserListing/UsersListing";
$route['UsersListing/(:any)'] = "AllUserListing/UsersListing/$1";
$route['userstatus/(:any)'] = "AllUserListing/userstatus/$1";
$route['userdelete/(:any)'] = "AllUserListing/userdelete/$1";
$route['userDetail/(:any)'] = "AllUserListing/userDetail/$1";

$route['createUserXLS'] = "AllUserListing/createUserXLS";

$route['DriversListing/(:any)'] = "AllDriverListing/DriversListing/$1";
$route['driverDetail/(:any)'] = "AllDriverListing/driverDetail/$1";
$route['driverdelete/(:any)'] = "AllDriverListing/driverdelete/$1";
$route['notifyDriver/(:any)'] = "AllDriverListing/notifyDriver/$1";
$route['submitDocuments'] = "AllDriverListing/submitDocuments";

$route['createDriverXLS'] = "AllDriverListing/createDriverXLS";

$route['RideListing'] = 'AllRideListing/RideListing';

$route['CancelRideListing'] = 'AllCancelRideListing/CancelRideListing';

$route['BlankRideListing'] = 'AllBlankRideListing/BlankRideListing';

$route['RideCancelDetail/(:any)'] = 'AllCancelRideListing/RideCancelDetail/$1';

$route['RideCancelDetailNotification/(:any)'] = 'AllCancelRideListing/RideCancelDetailNotification/$1';

$route['RideListing/(:any)'] = "AllRideListing/RideListing/$1";
$route['RideDetail/(:any)'] = "AllRideListing/RideDetail/$1";
$route['createRideXLS'] = 'AllRideListing/createRideXLS';
$route['createArticleXLS'] = 'AllArticleListing/createArticleXLS';


$route['ArticleListing'] = 'AllArticleListing/ArticleListing';
$route['ArticleListing/(:any)'] = "AllArticleListing/ArticleListing/$1";
$route['AddArticle'] = 'AllArticleListing/AddArticle';
$route['addarticledata'] = 'AllArticleListing/addarticledata';
$route['deleteArticle/(:any)'] = "AllArticleListing/deleteArticle/$1";
$route['articleEditDetail/(:any)'] = "AllArticleListing/articleEditDetail/$1";
$route['editarticledata'] = 'AllArticleListing/editarticledata';


$route['VechicleListing'] = 'AllVechicleListing/VechicleListing';
$route['createVechicleXLS'] = 'AllVechicleListing/createVechicleXLS';

$route['AddVechicle'] = 'AllVechicleListing/AddVechicle';
$route['AddVechicledata'] = 'AllVechicleListing/AddVechicledata';
$route['deletevechicle/(:any)'] = "AllVechicleListing/deletevechicle/$1";

$route['vehicleDetails/(:any)'] = "AllVechicleListing/vehicleDetails/$1";

$route['vehicleEditDetails/(:any)'] = "AllVechicleListing/vehicleEditDetails/$1";
$route['editvechicledata'] = 'AllVechicleListing/editvechicledata';

$route['VechicleListing/(:any)'] = "AllVechicleListing/VechicleListing/$1";




$route['SettingListing'] = "AllSettingListing/SettingListing";

$route['createSettingXLS'] = "AllSettingListing/createSettingXLS";

$route['SettingListing/(:any)'] = "AllSettingListing/SettingListing/$1";


$route['settingEditDetail/(:any)'] = "AllSettingListing/settingEditDetail/$1";



$route['editsettingdata'] = 'AllSettingListing/editsettingdata';


$route['SubadminListing'] = "AllSubadminListing/SubadminListing";
$route['SubadminListing/(:any)'] = "AllSubadminListing/SubadminListing/$1";
$route['AddSubadmin'] = "AllSubadminListing/AddSubadmin";
$route['SubadminEditDetail/(:any)'] = "AllSubadminListing/SubadminEditDetail/$1";
$route['editsubadmindata'] = "AllSubadminListing/editsubadmindata";

$route['deleteSubadmin/(:any)'] = "AllSubadminListing/deleteSubadmin/$1";

$route['addsubadmindata'] = "AllSubadminListing/addsubadmindata";
$route['createSubadminXLS'] = "AllSubadminListing/createSubadminXLS";
$route['ModulePermission/(:any)'] = "AllSubadminListing/ModulePermission/$1";
$route['addsubadminpermissiondata'] = "AllSubadminListing/addsubadminpermissiondata";

$route['CityListing'] = "AllCityListing/CityListing";
$route['deletecity/(:any)'] = "AllCityListing/deletecity/$1";
$route['createCityXLS'] = "AllCityListing/createCityXLS";
$route['AddCity'] = "AllCityListing/AddCity";
$route['addcitydata'] = "AllCityListing/addcitydata";
$route['viewall'] = "AllNotification/viewall";
$route['deletenotification/(:any)'] = "AllNotification/deletenotification/$1";
$route['AboutUsEditDetail'] = "AllAboutUsListing/AboutUsEditDetail";
$route['editaboutusdata'] = "AllAboutUsListing/editaboutusdata";

