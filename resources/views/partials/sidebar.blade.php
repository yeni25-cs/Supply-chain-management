<div class="bg-dark text-white p-3 shadow"
     style="width:250px;min-height:100vh;">

    <h4 class="mb-4">
        Supply Chain
    </h4>

    <ul class="nav flex-column">

        <li class="nav-item mb-2">

            <a href="{{ route('dashboard') }}"
               class="nav-link text-white">

                <i class="bi bi-speedometer2 me-2"></i>

                Dashboard

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="{{ route('suppliers.index') }}"
               class="nav-link text-white">

                <i class="bi bi-building me-2"></i>

                Suppliers

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="{{ route('ports.index') }}"
               class="nav-link text-white">

                <i class="bi bi-anchor me-2"></i>

                Ports

            </a>

        </li>

        <li class="nav-item mb-2">

            <a href="#"
               class="nav-link text-white">

                <i class="bi bi-cloud me-2"></i>

                API Logs

            </a>

        </li>

    </ul>

</div>