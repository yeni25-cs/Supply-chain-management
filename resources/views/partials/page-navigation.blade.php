<style>
.page-nav{
    display:flex;
    justify-content:center;
    margin:50px 0;
}

.nav-box{
    display:flex;
    border:1px solid #d0d0d0;
    border-radius:8px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}

.nav-box a,
.nav-box button{

    width:55px;
    height:45px;

    border:none;
    border-right:1px solid #d0d0d0;

    background:#fff;

    display:flex;
    justify-content:center;
    align-items:center;

    text-decoration:none;
    color:#444;

    font-weight:600;
    cursor:pointer;
}

.nav-box a:last-child,
.nav-box button:last-child{
    border-right:none;
}

.nav-box a:hover,
.nav-box button:hover{
    background:#0d6efd;
    color:white;
}

.active-page{
    background:#0d6efd !important;
    color:white !important;
}

.hidden{
    display:none;
}

#pageGroup1,
#pageGroup2{
    display:flex;
}

#pageGroup1.hidden,
#pageGroup2.hidden{
    display:none;
}
</style>


<div class="page-nav">

    <div class="nav-box">

        <button id="prevPage">&lt;</button>

        <div id="pageGroup1">

            <a href="{{ route('dashboard') }}"
               class="{{ request()->routeIs('dashboard') ? 'active-page' : '' }}"
               title="Dashboard">
                1
            </a>

            <a href="{{ route('ports.index') }}"
               class="{{ request()->routeIs('ports.index') ? 'active-page' : '' }}"
               title="Ports">
                2
            </a>

            <a href="{{ route('news.index') }}">
                3
            </a>

        </div>

        <div id="pageGroup2" class="hidden">

            <a class="page-link" href="{{ route('comparison.index') }}">
                    4
            </a>

            <a href="{{ route('admin.dashboard') }}"
               class="{{ request()->routeIs('admin.dashboard') ? 'active-page' : '' }}"
               title="Admin Dashboard">
                5
            </a>

        </div>

        <button id="nextPage">&gt;</button>

    </div>

</div>

<script>

const page1=document.getElementById('pageGroup1');
const page2=document.getElementById('pageGroup2');

document.getElementById('nextPage').onclick=function(){

    page1.classList.add('hidden');
    page2.classList.remove('hidden');

}

document.getElementById('prevPage').onclick=function(){

    page2.classList.add('hidden');
    page1.classList.remove('hidden');

}

</script>