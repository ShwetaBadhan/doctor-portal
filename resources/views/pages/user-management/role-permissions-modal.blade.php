<!-- ✅ Permissions Modal - Directly loop ke andar -->
<div class="modal fade" id="permissionsModal{{ $role->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="ti ti-shield-lock me-2 text-primary"></i>
                    Edit Permissions for: <span class="text-primary">{{ $role->name }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('roles.updatePermissions', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    @php
                        $rolePermissionNames = $role->permissions->pluck('name')->toArray();
                        $groupedPermissions = $allPermissions;
                    @endphp
                    
                    <div class="row">
                        @foreach($groupedPermissions as $group => $permissions)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card h-100 border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-bold text-uppercase small">
                                            <i class="ti ti-folder me-2"></i>{{ $group ?: 'General' }}
                                        </h6>
                                    </div>
                                    <div class="card-body p-2">
                                        @foreach($permissions as $permission)
                                            <div class="form-check mb-2">
                                                <input type="checkbox" 
                                                       class="form-check-input"
                                                       name="permissions[]"
                                                       value="{{ $permission->name }}"
                                                       id="perm_{{ $role->id }}_{{ $permission->id }}"
                                                       {{ in_array($permission->name, $rolePermissionNames) ? 'checked' : '' }}>
                                                <label class="form-check-label small" 
                                                       for="perm_{{ $role->id }}_{{ $permission->id }}">
                                                    {{ str_replace('-', ' ', ucfirst($permission->name)) }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check me-1"></i>Update Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>