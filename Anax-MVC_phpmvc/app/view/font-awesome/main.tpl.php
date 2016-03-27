<h1>Font Awesome</h1>

<section id="larger">
  <h2 class="page-header">
    Larger Icons
    <div class="pull-right text-default margin-top padding-top-sm hidden-xs">
    </div>
  </h2>
  <div class="row">
    <div class="col-md-3 col-sm-4">
      <p><i class="fa fa-camera-retro fa-lg"></i> fa-lg</p>
      <p><i class="fa fa-camera-retro fa-2x"></i> fa-2x</p>
      <p><i class="fa fa-camera-retro fa-3x"></i> fa-3x</p>
      <p><i class="fa fa-camera-retro fa-4x"></i> fa-4x</p>
      <p><i class="fa fa-camera-retro fa-5x"></i> fa-5x</p>
    </div>
    <div class="col-md-9 col-sm-8">
      <p>
        To increase icon sizes relative to their container, use the <code>fa-lg</code> (33% increase), <code>fa-2x</code>,
        <code>fa-3x</code>, <code>fa-4x</code>, or <code>fa-5x</code> classes.
      </p>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-camera-retro fa-lg"</span><span class="nt">&gt;&lt;/i&gt;</span> fa-lg
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-camera-retro fa-2x"</span><span class="nt">&gt;&lt;/i&gt;</span> fa-2x
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-camera-retro fa-3x"</span><span class="nt">&gt;&lt;/i&gt;</span> fa-3x
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-camera-retro fa-4x"</span><span class="nt">&gt;&lt;/i&gt;</span> fa-4x
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-camera-retro fa-5x"</span><span class="nt">&gt;&lt;/i&gt;</span> fa-5x
</code></pre></div>
      <div class="alert alert-success">
        <ul class="fa-ul">
          <li>
            <i class="fa fa-exclamation-triangle fa-li fa-lg"></i>
            If your icons are getting chopped off on top and bottom, make sure you have
            sufficient line-height.
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section id="animated">
  <h2 class="page-header">
    Animated Icons
    <div class="pull-right text-default margin-top padding-top-sm hidden-xs">
    </div>
  </h2>
  <div class="row">
    <div class="col-md-3 col-sm-4">
      <p>
        <i class="fa fa-spinner fa-spin fa-3x fa-fw margin-bottom"></i>
        <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw margin-bottom"></i>
        <i class="fa fa-refresh fa-spin fa-3x fa-fw margin-bottom"></i>
        <i class="fa fa-cog fa-spin fa-3x fa-fw margin-bottom"></i>
        <i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>
      </p>
    </div>
    <div class="col-md-9 col-sm-8">
      <p>
        Use the <code>fa-spin</code> class to get any icon to rotate, and use <code>fa-pulse</code> to have it rotate
        with 8 steps. Works well with <code>fa-spinner</code>, <code>fa-refresh</code>, and <code>fa-cog</code>.
      </p>
<div class="highlight"><pre><code class="html"><span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-spinner fa-spin"</span><span class="nt">&gt;&lt;/i&gt;</span>
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-circle-o-notch fa-spin"</span><span class="nt">&gt;&lt;/i&gt;</span>
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-refresh fa-spin"</span><span class="nt">&gt;&lt;/i&gt;</span>
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-cog fa-spin"</span><span class="nt">&gt;&lt;/i&gt;</span>
<span class="nt">&lt;i</span> <span class="na">class=</span><span class="s">"fa fa-spinner fa-pulse"</span><span class="nt">&gt;&lt;/i&gt;</span>
</code></pre></div>
      <p class="alert alert-success">
        <i class="fa fa-exclamation-triangle fa-lg"></i> 
        Some browsers on some platforms have issues with animated icons resulting in a jittery wobbling effect. See 
        <a href="https://github.com/FortAwesome/Font-Awesome/issues/671" class="alert-link" target="_blank">issue #671</a> 
        for examples and possible workarounds.
      </p>
      <p class="alert alert-success">
        <i class="fa fa-info-circle fa-lg"></i> CSS3 animations aren't supported in IE8 - IE9.
      </p>
    </div>
  </div>
</section>