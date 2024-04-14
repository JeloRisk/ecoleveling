<x-app-layout>
    <ul>
    @foreach ($rooms as $room)
    
        <li>{{$room->roomNumber}}</li>
    @endforeach
        </ul>




        <table class="table-auto">
            <thead>
              <tr>
                <th> Room 1</th>
                <th>Artist</th>
                <th>Year</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rooms as $room)
                <div class="bg-gray-100 rounded-lg p-4 shadow-md mb-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $room->name }}</h2>
                    <p class="text-gray-700">Type: {{ $room->type }}</p>
                    <p class="text-gray-700">Size: {{ $room->size }}</p>
                    <!-- Add more room details here -->
                </div>
              @endforeach

            </tbody>
          </table>
</x-app-layout>