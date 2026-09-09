<div>
    @if(window.LICENSE_GRACE)
    <div style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 12px 20px; text-align: center; font-weight: 500; position: sticky; top: 0; z-index: 9999;">
        ⚠️ License expired. Contact vendor to renew. Features restricted — {{ window.LICENSE_GRACE_DAYS }} days until full lock.
    </div>
    @endif
</div>
