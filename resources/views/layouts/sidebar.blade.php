{{-- <div class="bg-gray-800 text-white h-full flex flex-col justify-between">
    <div class="p-4">
        <h2 class="text-lg font-semibold mb-4">Sidebar</h2>
        <ul>
            <li><a href="#" class="block py-2">Link 1</a></li>
            <li><a href="#" class="block py-2">Link 2</a></li>
            <li><a href="#" class="block py-2">Link 3</a></li>
        </ul>
    </div>
    <div class="p-4">
        <!-- Footer content here -->
    </div>
</div> --}}
<style>
    #mySidenav a {
      position: absolute;
      left: -80px;
      transition: 0.3s;
      padding: 15px;
      width: 100px;
      text-decoration: none;
      font-size: 20px;
      color: white;
      border-radius: 0 5px 5px 0;
    }
    
    #mySidenav a:hover {
      left: 0;
    }
    
    #role {
      top: 20px;
      background-color: #04AA6D;
    }
    
    #otp {
      top: 80px;
      background-color: #2196F3;
    }
    
    #projects {
      top: 140px;
      background-color: #f44336;
    }
    
    #contact {
      top: 200px;
      background-color: #555
    }
    </style>
<div id="mySidenav" class="sidenav">
    <a href="{{ route('roles.index') }}" id="role">Role</a>
    <a href="{{ route('otp') }}" id="otp">Otp</a>
    {{-- <a href="#" id="projects">Projects</a>
    <a href="#" id="contact">Contact</a> --}}
  </div>