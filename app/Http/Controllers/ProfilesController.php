<?php

    namespace App\Http\Controllers;


    use App\Activity;
    use App\User;
    use Illuminate\Database\Eloquent\Collection;

    class ProfilesController extends Controller
    {
        public function show(User $user)
        {
            //            return $activities;

            return view('profiles.show', [
                'profileUser' => $user,
//                'activities' => $this->getActivity($user)
                'activities' => Activity::feed($user)
            ]);
        }
    }
