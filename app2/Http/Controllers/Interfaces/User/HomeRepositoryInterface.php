<?php
/**
 * Created by PhpStorm.
 * User: Al Mohands
 * Date: 22/05/2019
 * Time: 01:52 م
 */

namespace App\Http\Controllers\Interfaces\User;


interface HomeRepositoryInterface
{
    public function memberships($lang);
    public function subscribeMembership($user_id,$request,$lang);
    public function countries($lang);
    public function govsAndCities($request,$lang);
    public function category($lang);
    public function shopsOfCategory($request,$lang);
    public function shopDetails($request,$user,$lang);
    public function offerDetails($request,$lang);
    public function followAndUnfollow($user_id,$request,$lang);
    public function blockAndUnblock($shop_id,$request,$lang);
    public function getReviews($request,$lang);
    public function addReview($user_id,$request,$lang);
    public function deleteReview($request,$lang);

    public function followingList($user_id,$is_shop,$lang);
    public function blockingList($shop_id,$lang);

    public function addOffer($request,$id,$lang);
    public function editOffer($request,$lang);
    public function deleteOffer($request);

    public function phoneList($id);
    public function deletePhone($request);
    public function addPhone($request,$shop_id);
    public function filterMinMaxPrices($lang);
    
    public function search($request,$lang);
    public function searchByName($request,$lang);

    public function aboutUs($lang);
    public function terms($lang);
    public function complains_suggestions($request,$lang);
    public function notifications($user_id,$lang);
    
}
