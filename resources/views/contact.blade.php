@extends('layouts.app')

@section('content')
    <div class="page-header">
        <h1>Contact Us</h1>
    </div>

    <div class="contact-form">
        <form action="{{ route('contact.send') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>

    <div class="contact-info">
        <div class="info-item">
            <i class="fas fa-map-marker-alt"></i>
            <p>123 Farm Road, Countryside</p>
        </div>
        <div class="info-item">
            <i class="fas fa-phone"></i>
            <p>+123 456 7890</p>
        </div>
        <div class="info-item">
            <i class="fas fa-envelope"></i>
            <p>info@supafarm.com</p>
        </div>
    </div>
@endsection