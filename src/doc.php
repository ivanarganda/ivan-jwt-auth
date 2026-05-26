<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$baseUrl = rtrim($protocol . '://' . $_SERVER['HTTP_HOST'] . $basePath, '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ivan/jwt-auth — API Documentation</title>
</head>
<body>

<button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Open menu">☰</button>
<div class="sidebar-backdrop" id="sidebarBackdrop" hidden></div>
<div id="toastContainer" class="toast-container" aria-live="polite"></div>

<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-logo">JWT</div>
            <div class="brand-text">
                <h2>ivan/jwt-auth</h2>
                <p>API Documentation</p>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Status</div>
            <div class="api-status-card">
                <span class="status-dot ok"></span>
                <div>
                    <strong>Register</strong>
                    <p>Live · dynamic schema</p>
                </div>
            </div>
            <div class="api-status-card">
                <span class="status-dot pending"></span>
                <div>
                    <strong>Login + JWT</strong>
                    <p>Coming soon</p>
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">Endpoints</div>
            <a class="sidebar-link active" href="#overview">Overview</a>
            <a class="sidebar-link" href="#summary">Summary</a>
            <a class="sidebar-link" href="#endpoint-show">GET /show</a>
            <a class="sidebar-link" href="#endpoint-register">POST /register</a>
            <a class="sidebar-link" href="#endpoint-login">POST /login</a>
            <a class="sidebar-link" href="#auth-flow">Auth flow</a>
            <a class="sidebar-link" href="#request-history">Request history</a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-title">JWT Token</div>
            <div class="sidebar-token-box">
                <p id="sidebarTokenState">No JWT token saved yet.</p>
                <button type="button" class="small-btn" id="clearStoredTokenBtn">Clear saved token</button>
            </div>
        </div>
    </aside>

    <main class="content">
        <section class="hero section" id="overview">
            <div class="eyebrow">Base PHP · PDO · MySQL · JWT-ready</div>
            <h1>ivan/jwt-auth</h1>
            <p>
                Interactive documentation to test the API without Postman. Registration adapts
                automatically to your <code>users</code> table columns. Login and JWT are coming next.
            </p>
            <div class="hero-actions">
                <a href="#endpoint-register" class="btn btn-primary">Try registration</a>
                <a href="#endpoint-login" class="btn btn-secondary">View login (stub)</a>
            </div>
        </section>

        <section class="section" id="summary">
            <div class="section-head">
                <h2>Project summary</h2>
                <p>Modular REST API with validation, repositories, and dynamic registration based on your MySQL schema.</p>
            </div>

            <div class="grid grid-3">
                <div class="card">
                    <div class="mini-stat">JWT</div>
                    <p>Protected routes with <code>Authorization: Bearer</code> — in development.</p>
                </div>
                <div class="card">
                    <div class="mini-stat">3</div>
                    <p>Documented endpoints: health, register, and login (stub).</p>
                </div>
                <div class="card">
                    <div class="mini-stat">Try it</div>
                    <p>Send real requests from the browser and review local history.</p>
                </div>
            </div>

            <div class="grid grid-2" style="margin-top:20px;">
                <div class="card">
                    <h3>Dynamic registration</h3>
                    <p>
                        Required client fields: <code>first_name</code>, <code>email</code>, <code>password</code>.
                        The server generates <code>uuid</code>, timestamps, and default <code>role</code> / <code>status</code>.
                        Any other table column (e.g. <code>last_name</code>) can be sent if it exists in MySQL.
                    </p>
                </div>
                <div class="card">
                    <h3>Base URL</h3>
                    <div class="base-url" id="baseUrlText"><?php echo htmlspecialchars($baseUrl); ?></div>
                    <div class="action-row" style="margin-top:12px;">
                        <button type="button" class="btn btn-secondary btn-small" id="copyBaseUrlBtn">Copy URL</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-show">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method get">GET</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/show</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/show">Copy Endpoint</button>
                    </div>
                    <h2>List users</h2>
                    <p>Returns users from the <code>users</code> table (hidden fields excluded). Supports pagination via query string.</p>
                </div>

                <div class="endpoint-tabs">
                    <button type="button" class="tab-btn active" data-target="show-overview">Overview</button>
                    <button type="button" class="tab-btn" data-target="show-request">Request</button>
                    <button type="button" class="tab-btn" data-target="show-response">Response</button>
                    <button type="button" class="tab-btn" data-target="show-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="show-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">No Body</span>
                        <span class="tag">Dynamic columns</span>
                    </div>
                    <p style="margin-top:16px;">
                        Columns in the response match your table schema. Soft-deleted rows are excluded when <code>deleted_at</code> exists.
                    </p>
                </div>

                <div class="endpoint-tab-content" id="show-request">
                    <h4>Query parameters</h4>
                    <div class="table-wrap" style="margin-bottom:18px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Param</th>
                                    <th>Required</th>
                                    <th>Default</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>limit</td>
                                    <td>No</td>
                                    <td>50</td>
                                    <td>Max rows to return (1–100).</td>
                                </tr>
                                <tr>
                                    <td>offset</td>
                                    <td>No</td>
                                    <td>0</td>
                                    <td>Pagination offset.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>No</td>
                                    <td>string</td>
                                    <td>Optional for GET requests.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="show-response">
                    <h4>Success (200)</h4>
<pre>{
  "status": "success",
  "message": "Users retrieved successfully",
  "data": [
    {
      "id": 1,
      "uuid": "…",
      "first_name": "Ivan",
      "email": "ivan@email.com",
      "role": "user",
      "status": "active"
    }
  ],
  "meta": {
    "total": 1,
    "count": 1,
    "limit": 50,
    "offset": 0
  }
}</pre>
                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s200">200 OK</span>
                        <span class="status-code s422">422 Validation</span>
                        <span class="status-code s405">405 Method</span>
                        <span class="status-code s500">500 Server Error</span>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="show-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/show?limit=50&amp;offset=0" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="show" placeholder="Bearer token if needed">
                            </div>
                            <div class="action-row">
                                <button type="button" class="btn btn-primary try-btn" data-endpoint-key="show">Send request</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: GET</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-register">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method post">POST</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/register</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/register">Copy Endpoint</button>
                    </div>
                    <h2>Register user</h2>
                    <p>Creates a user in <code>users</code>. Validates input, reads DB columns, and inserts allowed fields only.</p>
                </div>

                <div class="endpoint-tabs">
                    <button type="button" class="tab-btn active" data-target="register-overview">Overview</button>
                    <button type="button" class="tab-btn" data-target="register-request">Request</button>
                    <button type="button" class="tab-btn" data-target="register-response">Response</button>
                    <button type="button" class="tab-btn" data-target="register-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="register-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">JSON</span>
                        <span class="tag">Dynamic schema</span>
                    </div>
                    <div class="notice notice-info" style="margin-top:16px;">
                        <strong>Client sends:</strong> <code>first_name</code>, <code>email</code>, <code>password</code>
                        (min. 8 characters and one number). Optional: <code>last_name</code> and other table columns.
                    </div>
                    <div class="notice notice-muted" style="margin-top:12px;">
                        <strong>Server generates:</strong> <code>uuid</code>, <code>created_at</code>, <code>updated_at</code>,
                        <code>role</code> = <code>user</code>, <code>status</code> = <code>active</code> (unless you override them).
                    </div>
                </div>

                <div class="endpoint-tab-content" id="register-request">
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Must be application/json.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Body parameters</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>first_name</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>First name (min. 2 characters).</td>
                                </tr>
                                <tr>
                                    <td>last_name</td>
                                    <td>No</td>
                                    <td>string</td>
                                    <td>Last name, if the column exists in the table.</td>
                                </tr>
                                <tr>
                                    <td>email</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Unique email.</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Min. 8 characters and at least one digit.</td>
                                </tr>
                                <tr>
                                    <td>role / status</td>
                                    <td>No</td>
                                    <td>string</td>
                                    <td>Optional; defaults to <code>user</code> / <code>active</code>.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Example</h4>
<pre>{
  "first_name": "Ivan",
  "last_name": "Gonzalez",
  "email": "ivan@email.com",
  "password": "secret12"
}</pre>
                </div>

                <div class="endpoint-tab-content" id="register-response">
                    <h4>Success (201)</h4>
<pre>{
  "status": "success",
  "message": "User registered successfully",
  "data": {
    "id": 1,
    "uuid": "…",
    "first_name": "Ivan",
    "email": "ivan@email.com",
    "role": "user",
    "status": "active"
  }
}</pre>

                    <h4 style="margin-top:18px;">Errors</h4>
<pre>{
  "status": "error",
  "message": "Validation failed",
  "errors": { "password": "…" }
}</pre>

                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s201">201 Created</span>
                        <span class="status-code s409">409 Conflict</span>
                        <span class="status-code s422">422 Validation</span>
                        <span class="status-code s400">400 Bad Request</span>
                        <span class="status-code s405">405 Method</span>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="register-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/register" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="register" placeholder="Usually not needed for register">
                            </div>
                            <div class="field">
                                <label>JSON Body</label>
                                <textarea class="try-body" data-endpoint-key="register">{
  "first_name": "Ivan",
  "last_name": "Gonzalez",
  "email": "ivan@email.com",
  "password": "secret12"
}</textarea>
                            </div>
                            <div class="action-row">
                                <button type="button" class="btn btn-primary try-btn" data-endpoint-key="register">Send request</button>
                                <button type="button" class="btn btn-secondary btn-small format-btn" data-endpoint-key="register">Format JSON</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: POST</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="endpoint-login">
            <div class="card endpoint-shell">
                <div class="endpoint-top">
                    <div class="endpoint-header">
                        <div class="endpoint-main">
                            <span class="method post">POST</span>
                            <span class="endpoint-url"><?php echo htmlspecialchars($baseUrl); ?>/login</span>
                        </div>
                        <button class="btn btn-secondary btn-small copy-endpoint-btn" data-endpoint="<?php echo htmlspecialchars($baseUrl); ?>/login">Copy Endpoint</button>
                    </div>
                    <h2>Login user</h2>
                    <p>Authenticate with email and password. Will return a JWT once implemented.</p>
                </div>

                <div class="endpoint-tabs">
                    <button type="button" class="tab-btn active" data-target="login-overview">Overview</button>
                    <button type="button" class="tab-btn" data-target="login-request">Request</button>
                    <button type="button" class="tab-btn" data-target="login-response">Response</button>
                    <button type="button" class="tab-btn" data-target="login-try">Try it</button>
                </div>

                <div class="endpoint-tab-content active" id="login-overview">
                    <div class="tag-row">
                        <span class="tag">Public</span>
                        <span class="tag">JSON</span>
                        <span class="tag badge-soon">Coming soon</span>
                    </div>
                    <div class="notice notice-warn" style="margin-top:16px;">
                        <strong>Current stub:</strong> the endpoint responds but does not validate credentials or issue a JWT yet.
                        Next: <code>LoginService</code>, token, and welcome email template.
                    </div>
                    <p style="margin-top:12px;">
                        When login returns a <code>token</code>, this page will save it to <code>localStorage</code> automatically.
                    </p>
                </div>

                <div class="endpoint-tab-content" id="login-request">
                    <h4>Headers</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Header</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Content-Type</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>Must be application/json.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Body Parameters</h4>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Field</th>
                                    <th>Required</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>email</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User email.</td>
                                </tr>
                                <tr>
                                    <td>password</td>
                                    <td>Yes</td>
                                    <td>string</td>
                                    <td>User password.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <h4 style="margin-top:18px;">Example Body</h4>
<pre>{
  "email": "ivan@email.com",
  "password": "secret12"
}</pre>
                </div>

                <div class="endpoint-tab-content" id="login-response">
                    <h4>Current response (stub)</h4>
<pre>{
  "status": false,
  "message": "Registration is currently unavailable"
}</pre>

                    <h4 style="margin-top:18px;">Expected response (JWT)</h4>
<pre>{
  "status": "success",
  "message": "Login successful",
  "token": "eyJhbGciOiJIUzI1NiIs..."
}</pre>

                    <div class="status-codes" style="margin-top:14px;">
                        <span class="status-code s200">200 OK</span>
                        <span class="status-code s401">401 Unauthorized</span>
                        <span class="status-code s422">422 Validation Error</span>
                        <span class="status-code s500">500 Server Error</span>
                    </div>

                    <h4 style="margin-top:18px;">Stored token</h4>
                    <div class="token-box" id="storedTokenBox">No token stored yet.</div>
                    <div class="action-row" style="margin-top:12px;">
                        <button type="button" class="btn btn-secondary btn-small" id="copyStoredTokenBtn">Copy stored token</button>
                    </div>
                </div>

                <div class="endpoint-tab-content" id="login-try">
                    <div class="try-layout">
                        <div>
                            <div class="field">
                                <label>Request URL</label>
                                <input type="text" value="<?php echo htmlspecialchars($baseUrl); ?>/login" readonly>
                            </div>
                            <div class="field">
                                <label>Authorization Token (optional)</label>
                                <input type="text" class="try-token" data-endpoint-key="login" placeholder="Usually not needed for login">
                            </div>
                            <div class="field">
                                <label>JSON Body</label>
                                <textarea class="try-body" data-endpoint-key="login">{
  "email": "ivan@email.com",
  "password": "secret12"
}</textarea>
                            </div>
                            <div class="action-row">
                                <button type="button" class="btn btn-primary try-btn" data-endpoint-key="login">Send request</button>
                                <button type="button" class="btn btn-secondary btn-small format-btn" data-endpoint-key="login">Format JSON</button>
                                <button type="button" class="btn btn-secondary btn-small" id="useStoredTokenBtn">Use stored token</button>
                            </div>
                        </div>
                        <div>
                            <div class="response-meta">
                                <div class="pill try-status">Status: -</div>
                                <div class="pill try-method">Method: POST</div>
                                <div class="pill try-time">Time: -</div>
                            </div>
                            <pre class="try-response">No request sent yet.</pre>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" id="auth-flow">
            <div class="section-head">
                <h2>Authentication flow</h2>
                <p>Expected API lifecycle (register is live; login/JWT in the next iteration).</p>
            </div>

            <div class="flow">
                <div class="card flow-item">
                    <div class="step">1</div>
                    <h4>Register</h4>
                    <p>Client submits user data to create the account.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">2</div>
                    <h4>Validate & Store</h4>
                    <p>Backend validates the payload and stores the user securely.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">3</div>
                    <h4>Login</h4>
                    <p>User authenticates with email and password.</p>
                </div>
                <div class="card flow-item">
                    <div class="step">4</div>
                    <h4>Receive JWT</h4>
                    <p>The token is returned and used for protected requests.</p>
                </div>
            </div>
        </section>

        <section class="section" id="request-history">
            <div class="section-head">
                <h2>Request history</h2>
                <p>Latest tests sent from this page (stored locally in your browser only).</p>
            </div>

            <div class="card">
                <div class="action-row" style="margin-bottom:14px;">
                    <button type="button" class="btn btn-secondary btn-small" id="clearHistoryBtn">Clear history</button>
                </div>
                <div class="history-list" id="historyList">
                    <p class="muted">No requests stored yet.</p>
                </div>
            </div>
        </section>

        <div class="footer">
            <p>ivan/jwt-auth — API Documentation · Register live · Login/JWT coming soon</p>
        </div>
    </main>
</div>
</body>
</html>