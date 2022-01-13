<?php
$level = Auth::user()->role; ?>
<div class="sidebar" data-background-color="dark">	
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="avatar-sm float-left mr-2">
                    <img src="{{ asset('/assets/img/profile.jpg')}}" alt="..." class="avatar-img rounded-circle">
                </div>
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            {{ Auth::user()->name }}
                            <span class="user-level">Administrator</span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>

                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="#profile">
                                    <span class="link-collapse">My Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="#edit">
                                    <span class="link-collapse">Edit Profile</span>
                                </a>
                            </li>
                            <li>
                                <a href="#settings">
                                    <span class="link-collapse">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-warning">
                <li class="nav-item {{ isset($m_dashboard) ? $m_dashboard : '' }}">
                    <a href="{{ url('home') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (in_array($level, [1]))
                <li class="nav-item">
                    <a href="#">
                        <i class="fas fa-home"></i>
                        <p>Exam</p>
                    </a>
                </li>
                @endif
                @if (in_array($level, [0]))
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Quiz Setting</h4>
                </li>
                <li class="nav-item {{ isset($m_bank_question) ? $m_bank_question : '' }}">
                    <a href="{{ url('bank_question') }}">
                        <i class="far fa-file-excel"></i>
                        <p>Bank Questions</p>                        
                    </a>
                </li>
                <li class="nav-item">
                    <a href="starter-template.html">
                        <i class="far fa-file-excel"></i>
                        <p>Exam</p>
                    </a>
                </li>
                @endif
                @if (in_array($level, [0]))
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Setting</h4>
                </li>
                
                <li class="nav-item {{ isset($m_user) ? $m_user : '' }}">
                    <a href="{{ url('manage_user') }}">
                        <i class="far fa-user"></i>
                        <p>Management User</p>
                    </a>
                </li>
                <li class="nav-item {{ isset($m_category) ? $m_category : '' }}">
                    <a href="{{ url('category') }}">
                        <i class="far fa-user"></i>
                        <p>Category</p>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</div>