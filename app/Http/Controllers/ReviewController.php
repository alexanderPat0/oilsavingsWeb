<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\Auth\EmailExists;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerificationEmail;

class ReviewController extends Controller
{
    protected $database;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('firebase.json'))
            ->withDatabaseUri(env("FIREBASE_DATABASE_URL"));

        $this->database = $firebase->createDatabase();
    }

    // Display a listing of the reviews
    public function index()
    {
        $reviewsSnapshot = $this->database->getReference('placeReview')->getSnapshot();
        $allReviews = $reviewsSnapshot->getValue() ?? [];  // Asegura que siempre sea un array

        $reviewsByPlace = [];
        foreach ($allReviews as $review) {
            $placeName = $review['placeName'];
            $reviewsByPlace[$placeName][] = $review;  // Agrupa por placeName
        }

        return view('users.review-list', ['reviewsByPlace' => $reviewsByPlace]);
    }

    // Store a newly created review
    public function store(Request $request)
    {
        $request->validate([
            'placeId' => 'required|string',
            'placeName' => 'required|string',
            'userId' => 'required|string',
            'username' => 'required|string',
            'review' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $reviewData = [
            'placeIduserId' => $request->placeId.$request->userId,
            'placeId' => $request->placeId,
            'placeName' => $request->placeName,
            'userId' => $request->userId,
            'username' => $request->username,
            'review' => $request->review,
            'rating' => $request->rating,
            'date' => now()->toString(),
        ];

        // Save the review in Firebase
        $this->database->getReference('placeReview')->push($reviewData);

        return response()->json(['success' => true, 'message' => 'Review successfully saved.']);
    }

    // Edit an existing review
    public function edit($id)
    {
        $review = $this->database->getReference('placeReview')->getChild($id)->getValue();
        return view('users.review-edit', compact('review', 'id'));
    }

    // Update an existing review
    public function update(Request $request, $id)
    {
        $request->validate([
            'review' => 'required|string',
            'rating' => 'required|numeric',
        ]);

        $reviewData = [
            'review' => $request->review,
            'rating' => $request->rating,
            'date' => now()->toString(),
        ];

        $this->database->getReference('placeReview/' . $id)->update($reviewData);

        return redirect()->route('users.review-list')->with('success', 'Review updated successfully.');
    }

    // Delete an existing review
    public function destroy($id)
    {
        $this->database->getReference('placeReview/' . $id)->remove();

        return redirect()->route('users.review-list')->with('success', 'Review deleted successfully.');
    }
}
