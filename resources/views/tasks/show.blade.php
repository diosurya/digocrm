<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">Back to List</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $task->title }}</h3>
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                            {{ $task->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}
                        ">
                            {{ ucfirst($task->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center text-gray-500">
                            <i class="fa-solid fa-calendar mr-2"></i>
                            <span>Due Date: {{ $task->due_date?->format('d M Y') ?: 'No due date' }}</span>
                        </div>
                        <div class="flex items-center text-gray-500">
                            <i class="fa-solid fa-flag mr-2"></i>
                            <span>Priority: {{ ucfirst($task->priority) }}</span>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h4 class="text-sm font-bold text-gray-700 uppercase mb-2">Description</h4>
                    <div class="text-gray-600 whitespace-pre-line bg-gray-50 p-4 rounded-lg border">
                        {{ $task->description ?: 'No description provided.' }}
                    </div>
                </div>

                <div class="mt-8 flex gap-3 border-t pt-6">
                    <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded text-sm uppercase">
                            Mark as {{ $task->status === 'pending' ? 'Completed' : 'Pending' }}
                        </button>
                    </form>
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-amber-500 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded text-sm uppercase">
                        Edit
                    </a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white font-bold py-2 px-6 rounded text-sm uppercase" onclick="return confirm('Are you sure?')">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
