<div class="bg-dark text-white p-3" style="width:250px;min-height:100vh;">

    <h4 class="mb-4">
        Supply Chain
    </h4>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">
            <a href="/" class="nav-link text-white">
                <i class="bi bi-speedometer2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="/suppliers" class="nav-link text-white">
                <i class="bi bi-building"></i>
                Suppliers
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="{{ route('products.index') }}" class="nav-link text-white">
                <i class="bi bi-box-seam"></i>
                Products
            </a>
<       /li>

        <li class="nav-item mb-2">
            <a href="{{ route('risk.index') }}" class="nav-link text-white">
                <i class="bi bi-shield-exclamation"></i>
                Risk Assessment
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="#" class="nav-link text-white">
                <i class="bi bi-cloud"></i>
                API Log
            </a>
        </li>

    </ul>

</div>