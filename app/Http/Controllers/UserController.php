<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;

class UserController extends Controller
{
    // Helper method for validation
    protected function validateUser(Request $request, $userId = null)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:10',
            'street' => 'required|string|max:255',
        ]);
    }

    // Helper method to handle user and address creation or update
    protected function saveUser($request, $userId = null)
    {
        // User data
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ];

        // Create or update user
        if ($userId) {
            $user = User::findOrFail($userId);
            $user->update($userData);
        } else {
            $user = User::create($userData);
        }

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'country' => $request->country,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'street' => $request->street,
            ]
        );

        return $user;
    }

    protected function searchUsers($search)
    {
        return User::with('address')
            ->when($search, function ($query) use ($search) {
                return $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $users = $this->searchUsers($search)->paginate(10);

        return Inertia::render('Dashboard', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateUser($request);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        $this->saveUser($request);

        return redirect()->route('users.index')->with('success', 'User added successfully');
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateUser($request, $id);

        if ($validated->fails()) {
            return back()->withErrors($validated)->withInput();
        }

        $this->saveUser($request, $id);

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $users = $this->searchUsers($search)->paginate(10);

        return response()->json($users);
    }

    public function edit(User $user)
    {
        return Inertia::render('UserForm', [
            'user' => $user
        ]);
    }

    public function show($id)
    {
        $user = User::with('address')->findOrFail($id);

        return Inertia::render('UserDetails', [
            'user' => $user,
        ]);
    }

}
