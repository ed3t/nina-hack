<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
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
        $pageSize = $request->get('pageSize', default: 5);
        $sortColumn = $request->get('sortColumn', default: 'created_at');
        $sortDirection = $request->get('sortDirection', default: 'desc');

        $users = $this->searchUsers($search)
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($pageSize);

        return Inertia::render('Dashboard', [
            'users' => $users,
            'pageSize' => $pageSize,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateUser($request);

        if ($validated->fails()) {
            return Redirect::route('users.index')
                ->withErrors($validated)
                ->withInput();
        }

        // If validation passes, save the user
        $user = $this->saveUser($request);

        // Return success message directly to the frontend and redirect to the user list
        return Redirect::route('users.index')->with('success', 'User updated successfully!');
    }

    public function update(Request $request, $id): RedirectResponse
    {
        // Validate user input
        $validated = $this->validateUser($request, $id);

        if ($validated->fails()) {
            return Redirect::route('users.edit', $id)
                ->withErrors($validated)
                ->withInput();
        }

        $userData = $request->except('email');

        // Save the user
        $user = $this->saveUser($request, $id);

        return Redirect::route('users.edit', $id)->with('success', 'User updated successfully!')
            ->with('user', $user);
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $user->address()->delete();
        $user->delete();

        return Redirect::route('users.index')->with('success', 'User deleted successfully!');
    }


    public function search(Request $request): JsonResponse
    {
        $search = $request->input('search');
        $perPage = 10;
        $users = $this->searchUsers($search)->paginate($perPage);

        return response()->json([
            'users' => $users,
            'pageSize' => $perPage,
        ]);
    }

    public function edit(User $user)
    {
        $user = User::with('address')->findOrFail($user->id);
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

    public function create(User $user)
    {
        return Inertia::render('UserForm', [
            'user' => $user
        ]);
    }
}
