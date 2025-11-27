<nav class="navbar navbar-expand-lg navbar-white bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ url('/') }}">
      <i class="bi bi-backpack text-primary" style="font-size: 1.5rem;"></i>
      <span class="text-primary">SchoolSupplies</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain" aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMain">
      <form class="d-flex mx-auto" role="search" method="GET" action="{{ route('products.index') }}">
        <input class="form-control me-2" name="search" type="search" placeholder="Search supplies, pens, notebooks..." aria-label="Search" value="{{ request('search') }}">
        <button class="btn btn-outline-primary" type="submit">Search</button>
      </form>

      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
        @auth
          @if(auth()->user()->isCustomer())
            <li class="nav-item me-3">
              <a class="nav-link position-relative" href="{{ route('customer.wishlist.index') }}">
                <i class="bi bi-heart fs-5"></i>
                @if(auth()->user()->wishlists()->count() > 0)
                  <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">{{ auth()->user()->wishlists()->count() }}</span>
                @endif
              </a>
            </li>
            <li class="nav-item me-3">
              <a class="nav-link position-relative" href="{{ route('customer.cart.index') }}">
                <i class="bi bi-cart fs-5"></i>
                @if(auth()->user()->cartItems()->count() > 0)
                  <span class="badge bg-primary rounded-pill position-absolute top-0 start-100 translate-middle">{{ auth()->user()->cartItems()->count() }}</span>
                @endif
              </a>
            </li>
          @endif
          @if(auth()->user()->isSeller())
            <li class="nav-item me-3">
              <a class="btn btn-sm btn-warning fw-semibold text-dark" href="{{ route('seller.products.create') }}">
                <i class="bi bi-plus-circle me-1"></i> Post Supplies
              </a>
            </li>
          @endif
        @endauth
        @guest
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2" href="{{ route('register') }}">Sign up</a></li>
        @else
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle"></i> {{ Auth::user()->name }}
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
              @if(auth()->user()->isCustomer())
                <li><a class="dropdown-item" href="{{ route('customer.orders.index') }}">Orders</a></li>
              @elseif(auth()->user()->isSeller())
                <li><a class="dropdown-item" href="{{ route('seller.dashboard') }}">Dashboard</a></li>
              @elseif(auth()->user()->isAdmin())
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
              @endif
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item" type="submit">Logout</button>
                </form>
              </li>
            </ul>
          </li>
        @endguest
      </ul>
    </div>
  </div>
</nav>

