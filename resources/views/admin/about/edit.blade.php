@extends('layouts.admin')

@section('title', 'Edit About Page - SupaFarm Admin')
@section('page-title', 'Edit About Page')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.about.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to About Page
            </a>
        </div>
    </div>

    <form action="{{ route('admin.about.update') }}" method="POST" enctype="multipart/form-data" id="aboutForm">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column - Main Content -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   id="title"
                                   name="title"
                                   value="{{ old('title', $about->title) }}"
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="introduction" class="form-label">Introduction <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('introduction') is-invalid @enderror rich-text-editor"
                                      id="introduction"
                                      name="introduction"
                                      rows="4"
                                      required>{{ old('introduction', $about->introduction) }}</textarea>
                            @error('introduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">A brief introduction that appears at the top of the about page.</div>
                        </div>

                        <div class="mb-3">
                            <label for="our_story" class="form-label">Our Story <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('our_story') is-invalid @enderror rich-text-editor"
                                      id="our_story"
                                      name="our_story"
                                      rows="6"
                                      required>{{ old('our_story', $about->our_story) }}</textarea>
                            @error('our_story')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Tell your company's story and history.</div>
                        </div>
                    </div>
                </div>

                <!-- Mission, Vision & Values -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Mission, Vision & Values</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="mission" class="form-label">Mission Statement <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('mission') is-invalid @enderror rich-text-editor"
                                      id="mission"
                                      name="mission"
                                      rows="3"
                                      required>{{ old('mission', $about->mission) }}</textarea>
                            @error('mission')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <div class="mb-3">
                            <label for="vision" class="form-label">Vision Statement <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('vision') is-invalid @enderror rich-text-editor"
                                      id="vision"
                                      name="vision"
                                      rows="3"
                                      required>{{ old('vision', $about->vision) }}</textarea>
                            @error('vision')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="values" class="form-label">Core Values <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('values') is-invalid @enderror rich-text-editor"
                                      id="values"
                                      name="values"
                                      rows="4"
                                      required>{{ old('values', $about->values) }}</textarea>
                            @error('values')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">List your company's core values. You can use bullet points or numbered lists.</div>
                        </div>
                    </div>
                </div>

                <!-- Team Section -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">Team Members</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addTeamMember()">
                            <i class="fas fa-plus me-1"></i>Add Member
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="team_description" class="form-label">Team Description</label>
                            <textarea class="form-control @error('team_description') is-invalid @enderror rich-text-editor"
                                      id="team_description"
                                      name="team_description"
                                      rows="3">{{ old('team_description', $about->team_description) }}</textarea>
                            @error('team_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">An introduction to your team section.</div>
                        </div>

                        <div id="teamMembersContainer">
                            @if(!empty($about->team_members) && count($about->team_members) > 0)
                                @foreach($about->team_members as $index => $member)
                                <div class="team-member-card card border mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2 text-center">
                                                @if(isset($member['image']))
                                                <img src="{{ asset('storage/' . $member['image']) }}"
                                                     alt="{{ $member['name'] }}"
                                                     class="rounded-circle mb-2"
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    <input type="file"
                                                           name="team_member_image[{{ $index }}]"
                                                           class="form-control form-control-sm"
                                                           accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label small fw-bold">Name</label>
                                                            <input type="text"
                                                                   name="team_member_name[{{ $index }}]"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $member['name'] }}"
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label class="form-label small fw-bold">Position</label>
                                                            <input type="text"
                                                                   name="team_member_position[{{ $index }}]"
                                                                   class="form-control form-control-sm"
                                                                   value="{{ $member['position'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-0">
                                                    <label class="form-label small fw-bold">Bio</label>
                                                    <textarea name="team_member_bio[{{ $index }}]"
                                                              class="form-control form-control-sm rich-text-editor-simple"
                                                              rows="2">{{ $member['bio'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="removeTeamMember(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div id="noTeamMembers" class="text-center py-4">
                                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No team members added yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Media Section -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Media</h5>
                    </div>
                    <div class="card-body">
                        <!-- Existing Images -->
                        @if(count($about->image_urls) > 0)
                        <div class="mb-4">
                            <label class="form-label fw-bold">Current Images</label>
                            <div class="row g-2">
                                @foreach($about->image_urls as $index => $imageUrl)
                                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                                    <div class="position-relative border">
                                        <img src="{{ $imageUrl }}"
                                             alt="About image {{ $index + 1 }}"
                                             class="img-fluid"
                                             style="height: 100px; width: 100%; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       name="keep_images[]"
                                                       value="{{ $index }}"
                                                       id="keepImage{{ $index }}"
                                                       checked>
                                                <label class="form-check-label text-white bg-success rounded px-1"
                                                       for="keepImage{{ $index }}"
                                                       style="font-size: 0.7rem;">
                                                    Keep
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- New Images Upload -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Upload New Images</label>
                            <input type="file"
                                   class="form-control @error('images') is-invalid @enderror"
                                   id="images"
                                   name="images[]"
                                   multiple
                                   accept="image/*">
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                You can select multiple images. Supported formats: JPEG, PNG, JPG, GIF, WEBP. Max size: 2MB per image.
                            </div>
                        </div>

                        <!-- Video URL -->
                        <div class="mb-0">
                            <label for="video_url" class="form-label">Video URL</label>
                            <input type="url"
                                   class="form-control @error('video_url') is-invalid @enderror"
                                   id="video_url"
                                   name="video_url"
                                   value="{{ old('video_url', $about->video_url) }}"
                                   placeholder="https://www.youtube.com/watch?v=...">
                            @error('video_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Enter a YouTube or Vimeo URL to embed a video.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4">
                <!-- Contact Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address"
                                      name="address"
                                      rows="3"
                                      required>{{ old('address', $about->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone', $about->phone) }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-0">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $about->email) }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">SEO Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   value="{{ old('meta_title', $about->meta_title) }}"
                                   maxlength="60">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 50-60 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description"
                                      name="meta_description"
                                      rows="3"
                                      maxlength="160">{{ old('meta_description', $about->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Recommended: 150-160 characters</div>
                        </div>

                        <div class="mb-0">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   value="{{ old('meta_keywords', $about->meta_keywords) }}">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Separate keywords with commas</div>
                        </div>
                    </div>
                </div>

                <!-- Publish Settings -->
                <div class="card border-0 mb-4">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Publish Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="published_at" class="form-label">Publish Date</label>
                            <input type="datetime-local"
                                   class="form-control @error('published_at') is-invalid @enderror"
                                   id="published_at"
                                   name="published_at"
                                   value="{{ old('published_at', $about->published_at ? $about->published_at->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Schedule when to publish the page</div>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="publish"
                                   value="1"
                                   id="publishNow"
                                   {{ $about->published_at ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="publishNow">
                                Publish Immediately
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card border-0">
                    <div class="card-header bg-light border-0">
                        <h5 class="card-title mb-0 fw-bold">Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.about.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <a href="{{ route('admin.about.preview') }}" target="_blank" class="btn btn-outline-info">
                                <i class="fas fa-external-link-alt me-2"></i>Preview
                            </a>
                        </div>

                        <!-- Character Counters -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-2">SEO Character Count</h6>
                            <div class="mb-2">
                                <small class="text-muted">Meta Title:</small>
                                <span id="metaTitleCount" class="badge bg-secondary float-end">0/60</span>
                            </div>
                            <div class="mb-0">
                                <small class="text-muted">Meta Description:</small>
                                <span id="metaDescCount" class="badge bg-secondary float-end">0/160</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Team Member Template -->
<template id="teamMemberTemplate">
    <div class="team-member-card card border mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2"
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user text-muted"></i>
                    </div>
                    <div>
                        <input type="file"
                               name="team_member_image[]"
                               class="form-control form-control-sm"
                               accept="image/*">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Name</label>
                                <input type="text"
                                       name="team_member_name[]"
                                       class="form-control form-control-sm"
                                       placeholder="John Doe"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Position</label>
                                <input type="text"
                                       name="team_member_position[]"
                                       class="form-control form-control-sm"
                                       placeholder="CEO">
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-bold">Bio</label>
                        <textarea name="team_member_bio[]"
                                  class="form-control form-control-sm rich-text-editor-simple"
                                  rows="2"
                                  placeholder="Brief bio..."></textarea>
                    </div>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeTeamMember(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- CKEditor 5 CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>

<script>
// Store CKEditor instances
const editorInstances = new Map();

// Initialize CKEditor
function initializeCKEditor(selector, config = {}) {
    const textareas = document.querySelectorAll(selector);

    textareas.forEach((textarea) => {
        // Don't reinitialize if already initialized
        if (editorInstances.has(textarea.id)) {
            return;
        }

        const defaultConfig = {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'bulletedList', 'numberedList', '|',
                    'link', 'insertTable', 'blockQuote', '|',
                    'undo', 'redo'
                ]
            },
            language: 'en',
            licenseKey: '',
            ...config
        };

        ClassicEditor
            .create(textarea, defaultConfig)
            .then(editor => {
                editorInstances.set(textarea.id, editor);

                // Update the original textarea when editor content changes
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    });
}

// Initialize editors when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Full-featured editor for main content
    initializeCKEditor('.rich-text-editor', {
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'fontColor', 'fontBackgroundColor', '|',
                'bulletedList', 'numberedList', '|',
                'alignment', 'outdent', 'indent', '|',
                'link', 'imageUpload', 'insertTable', 'blockQuote', 'mediaEmbed', '|',
                'undo', 'redo'
            ]
        },
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
            ]
        }
    });

    // Simplified editor for team member bios
    initializeCKEditor('.rich-text-editor-simple', {
        toolbar: {
            items: [
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'link', '|',
                'undo', 'redo'
            ]
        },
        shouldNotGroupWhenFull: true
    });

    // Initialize character counters
    initializeCharacterCounters();
});

let teamMemberCount = {{ !empty($about->team_members) ? count($about->team_members) : 0 }};

function addTeamMember() {
    const container = document.getElementById('teamMembersContainer');
    const template = document.getElementById('teamMemberTemplate');
    const clone = template.content.cloneNode(true);

    // Remove no members message if it exists
    const noMembers = document.getElementById('noTeamMembers');
    if (noMembers) {
        noMembers.remove();
    }

    container.appendChild(clone);
    teamMemberCount++;

    // Initialize CKEditor for the new textarea after a short delay
    setTimeout(() => {
        const newTextareas = container.querySelectorAll('.rich-text-editor-simple:not([data-ck-initialized])');
        newTextareas.forEach(textarea => {
            textarea.setAttribute('data-ck-initialized', 'true');
            initializeCKEditor(`#${textarea.id}`, {
                toolbar: {
                    items: [
                        'bold', 'italic', 'underline', '|',
                        'bulletedList', 'numberedList', '|',
                        'link', '|',
                        'undo', 'redo'
                    ]
                },
                shouldNotGroupWhenFull: true
            });
        });
    }, 100);
}

function removeTeamMember(button) {
    const card = button.closest('.team-member-card');
    const textarea = card.querySelector('textarea');

    // Destroy CKEditor instance if it exists
    if (textarea && editorInstances.has(textarea.id)) {
        editorInstances.get(textarea.id).destroy()
            .then(() => {
                editorInstances.delete(textarea.id);
                card.remove();
                teamMemberCount--;

                // Show no members message if no members left
                if (teamMemberCount === 0) {
                    const container = document.getElementById('teamMembersContainer');
                    container.innerHTML = `
                        <div id="noTeamMembers" class="text-center py-4">
                            <i class="fas fa-users fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No team members added yet</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error destroying CKEditor instance:', error);
            });
    } else {
        card.remove();
        teamMemberCount--;

        if (teamMemberCount === 0) {
            const container = document.getElementById('teamMembersContainer');
            container.innerHTML = `
                <div id="noTeamMembers" class="text-center py-4">
                    <i class="fas fa-users fa-2x text-muted mb-3"></i>
                    <p class="text-muted mb-0">No team members added yet</p>
                </div>
            `;
        }
    }
}

// Character counters
function initializeCharacterCounters() {
    const metaTitle = document.getElementById('meta_title');
    const metaDesc = document.getElementById('meta_description');
    const metaTitleCount = document.getElementById('metaTitleCount');
    const metaDescCount = document.getElementById('metaDescCount');

    function updateCounters() {
        if (metaTitle && metaTitleCount) {
            const titleLength = metaTitle.value.length;
            metaTitleCount.textContent = `${titleLength}/60`;
            metaTitleCount.className = `badge float-end ${titleLength <= 60 ? 'bg-success' : 'bg-danger'}`;
        }

        if (metaDesc && metaDescCount) {
            const descLength = metaDesc.value.length;
            metaDescCount.textContent = `${descLength}/160`;
            metaDescCount.className = `badge float-end ${descLength <= 160 ? 'bg-success' : 'bg-danger'}`;
        }
    }

    if (metaTitle) metaTitle.addEventListener('input', updateCounters);
    if (metaDesc) metaDesc.addEventListener('input', updateCounters);

    // Initialize counters
    updateCounters();
}

// Ensure form submission works with CKEditor
document.getElementById('aboutForm')?.addEventListener('submit', function() {
    // CKEditor automatically syncs with textareas, but we can force sync if needed
    editorInstances.forEach((editor, id) => {
        const textarea = document.getElementById(id);
        if (textarea) {
            textarea.value = editor.getData();
        }
    });
});
</script>

<style>
.card {
    border-radius: 0;
}

.btn {
    border-radius: 0;
    border: 1px solid;
}

.form-control {
    border-radius: 0;
    border: 1px solid #dee2e6;
}

.form-control:focus {
    border-color: #BC450D;
    box-shadow: none;
}

.form-check-input:checked {
    background-color: #BC450D;
    border-color: #BC450D;
}

.form-check-input:focus {
    border-color: #BC450D;
    box-shadow: none;
}

.invalid-feedback {
    display: block;
}

.badge {
    border-radius: 0;
    font-weight: 500;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Custom colors */
.btn-primary {
    background-color: #BC450D;
    border-color: #BC450D;
}

.btn-primary:hover {
    background-color: #a33a0b;
    border-color: #a33a0b;
}

.btn-outline-primary {
    color: #BC450D;
    border-color: #BC450D;
}

.btn-outline-primary:hover {
    background-color: #BC450D;
    border-color: #BC450D;
    color: white;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-info {
    color: #0dcaf0;
    border-color: #0dcaf0;
}

.btn-outline-info:hover {
    background-color: #0dcaf0;
    border-color: #0dcaf0;
    color: #000;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Team member cards */
.team-member-card {
    transition: all 0.2s ease;
}

.team-member-card:hover {
    border-color: #BC450D !important;
}

/* Image checkboxes */
.position-relative .form-check-input {
    background-color: #198754;
    border-color: #198754;
}

.position-relative .form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

/* Character counters */
.badge.bg-success { background-color: #198754 !important; }
.badge.bg-danger { background-color: #dc3545 !important; }
.badge.bg-secondary { background-color: #6c757d !important; }

/* Form sections */
.card-header {
    border-bottom: 1px solid #dee2e6;
}

/* CKEditor overrides */
.ck.ck-editor {
    border-radius: 0 !important;
    border: 1px solid #dee2e6 !important;
}

.ck.ck-editor:focus-within {
    border-color: #BC450D !important;
    box-shadow: none !important;
}

.ck.ck-toolbar {
    border-radius: 0 !important;
    border: none !important;
    border-bottom: 1px solid #dee2e6 !important;
}

.ck.ck-content {
    border-radius: 0 !important;
    border: none !important;
    min-height: 200px;
}

.ck.ck-content.ck-editor__editable_simple {
    min-height: 100px;
}

/* Hide original textareas since CKEditor replaces them */
.rich-text-editor,
.rich-text-editor-simple {
    display: none;
}
</style>
@endsection
